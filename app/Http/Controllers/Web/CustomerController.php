<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Farm;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private function getFarmId(): ?string
    {
        return session('active_farm_id') ?? Farm::first()?->id;
    }

    public function index(Request $request)
    {
        $farmId = $this->getFarmId();

        $query = Customer::where('farm_id', $farmId);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%')
                  ->orWhere('city', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->orderBy('total_revenue', 'desc')->paginate(20);

        $stats = [
            'total'           => Customer::where('farm_id', $farmId)->count(),
            'active'          => Customer::where('farm_id', $farmId)->where('status', 'active')->count(),
            'total_revenue'   => Customer::where('farm_id', $farmId)->sum('total_revenue'),
            'avg_order_value' => Customer::where('farm_id', $farmId)->where('total_orders', '>', 0)->avg('total_revenue') ?? 0,
            'new_this_month'  => Customer::where('farm_id', $farmId)->whereMonth('created_at', now()->month)->count(),
        ];

        return view('customers.index', compact('customers', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'whatsapp'      => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'type'          => 'required|in:butcher,wholesaler,retailer,individual',
            'company'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'country'       => 'nullable|string|max:255',
            'address'       => 'nullable|string',
            'notes'         => 'nullable|string',
            'total_revenue' => 'nullable|numeric|min:0',
            'total_orders'  => 'nullable|integer|min:0',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('customers', 'public');
        }

        $nameParts = explode(' ', $validated['name'], 2);
        $validated['first_name'] = $nameParts[0];
        $validated['last_name']  = $nameParts[1] ?? '';

        Customer::create([
            ...$validated,
            'country' => $validated['country'] ?? 'Algeria',
            'farm_id' => $this->getFarmId(),
            'status'  => 'active',
        ]);

        return redirect()->back()->with('success', 'Customer added successfully!');
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'type'    => 'required|in:butcher,wholesaler,retailer,individual',
            'company' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
            'status'  => 'nullable|in:active,inactive',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('customers', 'public');
        }

        if (isset($validated['name'])) {
            $nameParts = explode(' ', $validated['name'], 2);
            $validated['first_name'] = $nameParts[0];
            $validated['last_name']  = $nameParts[1] ?? '';
        }

        $customer->update($validated);

        return redirect()->back()->with('success', 'Customer updated successfully!');
    }

    public function destroy(string $id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Customer removed.');
    }
}
