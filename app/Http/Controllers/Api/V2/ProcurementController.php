<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\ProcurementOrder;
use App\Models\MedicineBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    public function index()
    {
        $orders = ProcurementOrder::with(['items.medicine', 'user'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Display current inventory levels based on medicine batches.
     */
    public function inventory()
    {
        $inventory = \App\Models\Medicine::with(['batches' => function($query) {
            $query->where('quantity_in_stock', '>', 0)
                  ->orderBy('expiry_date', 'asc');
        }])
        ->whereHas('batches', function($query) {
            $query->where('quantity_in_stock', '>', 0);
        })
        ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $inventory
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'nullable|exists:medicines,id',
            'items.*.item_name' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $order = ProcurementOrder::create([
                'order_number' => 'PO-' . strtoupper(uniqid()),
                'supplier_name' => $validated['supplier_name'],
                'order_date' => $validated['order_date'],
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $order->items()->create([
                    'medicine_id' => $item['medicine_id'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
                $total += $itemTotal;
            }

            $order->update(['total_amount' => $total]);

            return response()->json([
                'status' => 'success',
                'message' => 'Procurement order created',
                'data' => $order->load('items')
            ], 201);
        });
    }

    public function receive(Request $request, ProcurementOrder $order)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:procurement_items,id',
            'items.*.received_quantity' => 'required|integer|min:0',
            'items.*.batch_number' => 'nullable|string',
            'items.*.expiry_date' => 'nullable|date',
        ]);

        return DB::transaction(function () use ($validated, $order) {
            foreach ($validated['items'] as $itemData) {
                $item = $order->items()->find($itemData['id']);
                $item->update([
                    'received_quantity' => $itemData['received_quantity']
                ]);

                // If it's a medicine, update inventory
                if ($item->medicine_id && $itemData['received_quantity'] > 0) {
                    MedicineBatch::create([
                        'clinic_id' => $order->clinic_id,
                        'medicine_id' => $item->medicine_id,
                        'batch_number' => $itemData['batch_number'] ?? 'BN-' . strtoupper(uniqid()),
                        'expiry_date' => $itemData['expiry_date'],
                        'quantity_in_stock' => $itemData['received_quantity'],
                        'purchase_price' => $item->unit_price,
                    ]);
                }
            }

            $order->update(['status' => 'received']);

            return response()->json([
                'status' => 'success',
                'message' => 'Procurement order received and inventory updated',
                'data' => $order->load('items.medicine')
            ]);
        });
    }
}
