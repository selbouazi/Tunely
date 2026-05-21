<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Illuminate\Http\Request;

class GeneralDiscountController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'porcentaje' => 'required|numeric|min:1|max:99',
        ]);

        $percentage = (float) $request->porcentaje;
        $affected = 0;

        Instrument::whereNull('precio_original')->chunkById(100, function ($instruments) use ($percentage, &$affected) {
            foreach ($instruments as $instrument) {
                $instrument->update([
                    'precio_original' => $instrument->precio,
                    'precio' => round($instrument->precio * (1 - $percentage / 100), 2),
                    'descuento_general_applied' => true,
                ]);
                $affected++;
            }
        });

        return redirect()->back()->with('success', "Descuento general del {$percentage}% aplicado a {$affected} productos.");
    }

    public function remove()
    {
        $affected = 0;

        Instrument::where('descuento_general_applied', true)->chunkById(100, function ($instruments) use (&$affected) {
            foreach ($instruments as $instrument) {
                $instrument->update([
                    'precio' => $instrument->precio_original,
                    'precio_original' => null,
                    'descuento_general_applied' => false,
                ]);
                $affected++;
            }
        });

        return redirect()->back()->with('success', "Descuento general eliminado de {$affected} productos.");
    }
}
