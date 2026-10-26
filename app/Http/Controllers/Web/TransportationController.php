<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Vehicle;
use App\Models\Farm;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransportationController extends Controller
{
    private function getFarmId(): ?string
    {
        return session('active_farm_id') ?? Farm::first()?->id;
    }

    public function index(Request $request)
    {
        $farmId = $this->getFarmId();

        $query = Shipment::where('farm_id', $farmId)->with('vehicle');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('reference', 'like', '%'.$request->search.'%')
                  ->orWhere('destination', 'like', '%'.$request->search.'%')
                  ->orWhere('driver_name', 'like', '%'.$request->search.'%');
            });
        }

        $shipments = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        $vehicles   = Vehicle::where('farm_id', $farmId)->get();

        $stats = [
            'active'    => Shipment::where('farm_id', $farmId)->where('status', 'in_transit')->count(),
            'today'     => Shipment::where('farm_id', $farmId)->whereDate('scheduled_at', today())->count(),
            'delivered' => Shipment::where('farm_id', $farmId)->where('status', 'delivered')->count(),
            'scheduled' => Shipment::where('farm_id', $farmId)->where('status', 'scheduled')->count(),
            'vehicles_available' => Vehicle::where('farm_id', $farmId)->where('status', 'available')->count(),
            'vehicles_total'     => Vehicle::where('farm_id', $farmId)->count(),
        ];

        return view('transportation.index', compact('shipments', 'vehicles', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin'       => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'animal_count' => 'required|integer|min:1',
            'animal_type'  => 'nullable|string',
            'driver_name'  => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:50',
            'vehicle_id'   => 'nullable|string',
            'scheduled_at' => 'required|date',
            'distance_km'  => 'nullable|numeric',
            'notes'        => 'nullable|string',
        ]);

        $farmId = $this->getFarmId();

        Shipment::create([
            ...$validated,
            'farm_id'   => $farmId,
            'reference' => Shipment::generateReference(),
            'status'    => 'scheduled',
        ]);

        return redirect()->back()->with('success', 'Shipment scheduled successfully!');
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:scheduled,in_transit,delivered,cancelled']);

        $shipment = Shipment::findOrFail($id);
        $shipment->status = $request->status;

        if ($request->status === 'in_transit') {
            $shipment->departed_at = now();
        } elseif ($request->status === 'delivered') {
            $shipment->arrived_at = now();
        }

        $shipment->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'plate_number'     => 'nullable|string|max:50',
            'type'             => 'required|in:truck,trailer,van,pickup',
            'capacity_animals' => 'nullable|string',
            'driver_name'      => 'nullable|string|max:255',
            'driver_phone'     => 'nullable|string|max:50',
        ]);

        Vehicle::create([
            ...$validated,
            'farm_id' => $this->getFarmId(),
            'status'  => 'available',
        ]);

        return redirect()->back()->with('success', 'Vehicle added successfully!');
    }
}
