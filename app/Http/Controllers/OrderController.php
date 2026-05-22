<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function destroy(Order $order): RedirectResponse
    {
        $order->items()->delete();
        $order->pendingComments()->delete();
        $order->delete();

        return redirect()->route('admin.pedidos.index')
            ->with('success', "Pedido #{$order->id} eliminado correctamente.");
    }
    private array $transitions = [
        'pendiente' => ['pagado', 'cancelado'],
        'pagado' => ['enviado', 'cancelado'],
        'enviado' => ['entregado'],
        'entregado' => [],
        'cancelado' => [],
    ];

    public function index(): Response
    {
        $orders = Order::with('user', 'items.instrument')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load('user', 'items.instrument');

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order,
            'availableStatuses' => $this->transitions[$order->estado] ?? [],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => 'required|string|in:pendiente,pagado,enviado,entregado,cancelado',
        ]);

        $newStatus = $validated['estado'];
        $allowed = $this->transitions[$order->estado] ?? [];

        if (!in_array($newStatus, $allowed)) {
            return back()->withErrors(['estado' => "No se puede cambiar de '{$order->estado}' a '{$newStatus}'"]);
        }

        $order->update(['estado' => $newStatus]);

        return redirect()->route('admin.pedidos.show', $order->id)
            ->with('success', "Pedido #{$order->id} actualizado a '{$newStatus}'");
    }
}
