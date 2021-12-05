<?php

namespace App\Http\Controllers;

use App\Key;
use App\Order;
use App\Technician;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showOrders() {
        return view('orders', [
            'orders' => Order::get(),
            'keys' => Key::has('vehicle')->get(),
            'techs' => Technician::get(),
        ]);
    }

    public function store(Request $request) {
        try {
            $order = new Order();
            $order->key_id = $request->get('key');
            $order->technician_id = $request->get('tech');
            $order->save();

            return response()->json(['order' => $order->load('key', 'technician')]);
        } catch (Exception $e) {
            info($e);
            return response()->json(['error' => 'Error Saving Order' ], 400);
        }
    }

    public function update(Request $request, $id) {
        try {
            $order = Order::findOrFail($id);
            $order->key_id = $request->get('key');
            $order->technician_id = $request->get('tech');
            $order->save();

            return response()->json(['order' => $order->load('key', 'technician')]);
        } catch (Exception $e) {
            info($e);
            return response()->json(['error' => 'Error Updating Order' ], 400);
        }
    }

    public function destroy($id) {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order '.$id.' Deleted!']);
        } catch (Exception $e) {
            info($e);
            return response()->json(['error' => 'Error Deleting Order' ], 400);
        }
    }
}
