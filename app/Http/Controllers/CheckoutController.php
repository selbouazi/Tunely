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
        $user = auth()->user();

        return Inertia::render('Checkout', [
            'userData' => [
                'name' => $user->name,
                'telefono' => $user->telefono,
                'direccion' => $user->direccion,
                'ciudad' => $user->ciudad,
                'provincia' => $user->provincia,
                'codigo_postal' => $user->codigo_postal,
                'direccion_facturacion' => $user->direccion_facturacion,
                'ciudad_facturacion' => $user->ciudad_facturacion,
                'provincia_facturacion' => $user->provincia_facturacion,
                'codigo_postal_facturacion' => $user->codigo_postal_facturacion,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:instruments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_province' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_phone' => 'required|string|max:20',
            'billing_same_as_shipping' => 'boolean',
            'billing_name' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_address' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_city' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_province' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_postal_code' => 'required_if:billing_same_as_shipping,false|string|max:10',
            'card_number' => 'required|string|size:16',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|string|size:3',
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

            $billingSame = $validated['billing_same_as_shipping'] ?? true;

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => round($total, 2),
                'estado' => 'pendiente',
                'shipping_name' => $validated['shipping_name'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_province' => $validated['shipping_province'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_phone' => $validated['shipping_phone'],
                'billing_same_as_shipping' => $billingSame,
                'billing_name' => $billingSame ? $validated['shipping_name'] : $validated['billing_name'],
                'billing_address' => $billingSame ? $validated['shipping_address'] : $validated['billing_address'],
                'billing_city' => $billingSame ? $validated['shipping_city'] : $validated['billing_city'],
                'billing_province' => $billingSame ? $validated['shipping_province'] : $validated['billing_province'],
                'billing_postal_code' => $billingSame ? $validated['shipping_postal_code'] : $validated['billing_postal_code'],
            ]);

            foreach ($orderItems as $oi) {
                $order->items()->create($oi);

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
