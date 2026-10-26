<?php

namespace App\Services;

use App\Models\IotDevice;
use App\Models\IotSensor;
use App\Models\SensorReading;
use Illuminate\Support\Facades\Log;

class IoTService
{
    public function processTelemetry(string $macAddress, array $payload)
    {
        // Find device
        $device = IotDevice::where('mac_address', $macAddress)->first();

        if (!$device) {
            Log::warning("Telemetry received from unknown MAC: {$macAddress}");
            return ['status' => 'error', 'message' => 'Device not registered'];
        }

        // Update device status
        $device->update([
            'status' => 'online',
            'last_seen_at' => now(),
            'battery_level' => $payload['battery'] ?? $device->battery_level,
            'signal_strength' => $payload['rssi'] ?? $device->signal_strength,
        ]);

        $recordedCount = 0;

        // Process sensor readings
        if (isset($payload['sensors']) && is_array($payload['sensors'])) {
            foreach ($payload['sensors'] as $sensorType => $value) {
                $sensor = IotSensor::firstOrCreate(
                    [
                        'device_id' => $device->id,
                        'sensor_type' => $sensorType
                    ],
                    [
                        'unit_of_measurement' => $this->inferUnit($sensorType)
                    ]
                );

                SensorReading::create([
                    'sensor_id' => $sensor->id,
                    'value' => $value,
                    'recorded_at' => now()
                ]);
                
                $recordedCount++;

                // Threshold Check
                if ($sensor->safe_max && $value > $sensor->safe_max) {
                    $this->triggerAlert($device, $sensor, 'High threshold exceeded', $value);
                } elseif ($sensor->safe_min && $value < $sensor->safe_min) {
                    $this->triggerAlert($device, $sensor, 'Low threshold breached', $value);
                }
            }
        }

        // Emit WebSocket Event for Real-Time Dashboard (to be implemented via Pusher/Reverb)
        // event(new \App\Events\TelemetryReceived($device->id, $payload));

        return [
            'status' => 'success', 
            'message' => "Processed {$recordedCount} sensor readings.",
            'device_status' => 'online'
        ];
    }

    private function inferUnit(string $type): string
    {
        return match(strtolower($type)) {
            'temperature' => '°C',
            'humidity' => '%',
            'ammonia' => 'ppm',
            'water_level' => 'L',
            'weight' => 'kg',
            'battery' => 'V',
            default => ''
        };
    }

    private function triggerAlert(IotDevice $device, IotSensor $sensor, string $reason, float $value)
    {
        Log::alert("IoT Alert on {$device->name}: {$sensor->sensor_type} reading {$value} {$sensor->unit_of_measurement} - {$reason}");
        // Here we could hook into the AI_Alerts or Notifications schema
    }
}
