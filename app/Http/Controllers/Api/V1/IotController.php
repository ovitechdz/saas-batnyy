<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IoTService;
use App\Services\RfidService;

class IotController extends Controller
{
    protected $iotService;
    protected $rfidService;

    public function __construct(IoTService $iotService, RfidService $rfidService)
    {
        $this->iotService = $iotService;
        $this->rfidService = $rfidService;
    }

    /**
     * Endpoint for high-frequency sensor telemetry (ESP32, LoRaWAN, etc.)
     */
    public function telemetry(Request $request)
    {
        $request->validate([
            'mac_address' => 'required|string',
            'battery' => 'nullable|numeric',
            'rssi' => 'nullable|numeric',
            'sensors' => 'nullable|array',
        ]);

        $result = $this->iotService->processTelemetry($request->mac_address, $request->all());

        return response()->json($result, $result['status'] === 'error' ? 400 : 200);
    }

    /**
     * Endpoint for RFID/QR scanners
     */
    public function rfidScan(Request $request)
    {
        $request->validate([
            'reader_mac' => 'required|string',
            'epc_code' => 'required|string',
            'rssi' => 'nullable|numeric',
            'antenna_port' => 'nullable|integer',
        ]);

        $result = $this->rfidService->processScan(
            $request->reader_mac,
            $request->epc_code,
            $request->rssi,
            $request->antenna_port ?? 1
        );

        return response()->json($result, $result['status'] === 'error' ? 400 : 200);
    }
}
