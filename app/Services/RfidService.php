<?php

namespace App\Services;

use App\Models\RfidDevice;
use App\Models\RfidTag;
use App\Models\RfidLog;
use App\Models\Animal;
use Illuminate\Support\Facades\Log;

class RfidService
{
    public function processScan(string $readerMac, string $epcCode, ?float $rssi = null, ?int $antennaPort = 1)
    {
        $device = RfidDevice::where('mac_address', $readerMac)->first();

        if (!$device) {
            Log::warning("RFID Scan from unknown Reader MAC: {$readerMac}");
            return ['status' => 'error', 'message' => 'Reader not registered'];
        }

        // Update reader status
        $device->update([
            'status' => 'online',
            'last_ping_at' => now(),
        ]);

        $antenna = $device->antennas()->firstOrCreate(['port_number' => $antennaPort]);

        $tag = RfidTag::where('epc_code', $epcCode)->where('farm_id', $device->farm_id)->first();

        if (!$tag) {
            // Register an unknown tag event
            Log::info("Unknown RFID Tag Scanned: {$epcCode} at Reader {$device->name}");
            // Create the tag as unassigned
            $tag = RfidTag::create([
                'farm_id' => $device->farm_id,
                'epc_code' => $epcCode,
                'status' => 'unassigned',
            ]);
        }

        // Anti-Duplicate Logic: Prevent logging the exact same tag on the same antenna within 5 seconds
        $recentLog = RfidLog::where('rfid_tag_id', $tag->id)
            ->where('antenna_id', $antenna->id)
            ->where('read_at', '>=', now()->subSeconds(5))
            ->first();

        if ($recentLog) {
            return ['status' => 'ignored', 'message' => 'Duplicate scan filtered'];
        }

        RfidLog::create([
            'rfid_tag_id' => $tag->id,
            'antenna_id' => $antenna->id,
            'rssi' => $rssi,
            'read_at' => now()
        ]);

        // If tag is linked to an animal, we might want to update animal's last known location
        if ($tag->animal_id && $antenna->zone_id) {
            Animal::where('id', $tag->animal_id)->update(['pen_id' => $antenna->zone_id]);
        }

        return ['status' => 'success', 'message' => 'Scan recorded', 'animal_id' => $tag->animal_id];
    }
}
