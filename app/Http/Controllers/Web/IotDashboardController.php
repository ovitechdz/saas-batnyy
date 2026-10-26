<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IotDashboardController extends Controller
{
    public function index()
    {
        // Fetch devices using Query Builder to avoid unconfigured model errors
        $iotDevices = DB::table('iot_devices')->get();
        $rfidDevices = DB::table('rfid_devices')->get();

        // Get sensors and group them by device
        $sensors = DB::table('iot_sensors')->get()->groupBy('device_id');
        $iotDevices->transform(function ($device) use ($sensors) {
            $device->sensors = $sensors->get($device->id, collect());
            return $device;
        });

        // Get antennas and group them by device
        $antennas = DB::table('rfid_antennas')->get()->groupBy('rfid_device_id');
        $rfidDevices->transform(function ($device) use ($antennas) {
            $device->antennas = $antennas->get($device->id, collect());
            return $device;
        });

        $stats = [
            'total_devices' => collect($iotDevices)->count() + collect($rfidDevices)->count(),
            'online_devices' => collect($iotDevices)->where('status', 'online')->count() + collect($rfidDevices)->where('status', 'online')->count(),
            'offline_devices' => collect($iotDevices)->where('status', 'offline')->count() + collect($rfidDevices)->where('status', 'offline')->count(),
        ];

        return view('iot.dashboard', compact('iotDevices', 'rfidDevices', 'stats'));
    }
}
