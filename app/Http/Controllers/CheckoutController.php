<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Order;
use App\Models\PendingComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Checkout');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:instruments,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $instrument = Instrument::where('disponible', true)->find($item['id']);
                if (!$instrument) {
                    DB::rollBack();
                    return back()->withErrors(['items' => "El producto no está disponible"]);
                }
                if ($instrument->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->withErrors(['items' => "Stock insuficiente para {$instrument->marca} {$instrument->modelo}: disponible {$instrument->stock}"]);
                }

                $lineTotal = $instrument->precio * $item['quantity'];
                $total += $lineTotal;

                $orderItems[] = [
                    'instrument_id' => $instrument->id,
                    'cantidad' => $item['quantity'],
                    'precio_unitario' => $instrument->precio,
                ];

                $instrument->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => round($total, 2),
                'estado' => 'pendiente',
            ]);

            foreach ($orderItems as $oi) {
                $orderItem = $order->items()->create($oi);

                PendingComment::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'instrument_id' => $oi['instrument_id'],
                    'has_commented' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('checkout.success', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar el pedido. Inténtalo de nuevo.']);
        }
    }

    public function success(Order $order): Response
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.instrument', 'user');

        return Inertia::render('OrderSuccess', [
            'order' => $order,
        ]);
    }
}
