<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|regex:/^[\pL\s]+$/u|max:255',
            'apellido1' => 'nullable|string|regex:/^[\pL\s\-]+$/u|max:255',
            'apellido2' => 'nullable|string|regex:/^[\pL\s\-]+$/u|max:255',
            'fecha_nacimiento' => 'required|date|before:-18 years|after=-100 years',
            'telefono' => 'required|regex:/^\+?[0-9\s]{9,15}$/',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'codigo_postal' => 'required|regex:/^[0-9]{5}$/',
            'mismo_direccion_facturacion' => 'boolean',
            'direccion_facturacion' => 'nullable|string|max:255',
            'ciudad_facturacion' => 'nullable|string|max:100',
            'provincia_facturacion' => 'nullable|string|max:100',
            'codigo_postal_facturacion' => 'nullable|regex:/^[0-9]{5}$/',
            'preferencias_combustible' => 'nullable|string|max:50',
            'tipo_conduccion' => 'nullable|string|max:50',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre no puede contener números ni caracteres especiales',
            'apellido1.regex' => 'El apellido no puede contener números',
            'apellido2.regex' => 'El apellido no puede contener números',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'Debes tener al menos 18 años',
            'fecha_nacimiento.after' => 'La fecha no es válida',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener formato válido',
            'direccion.required' => 'La dirección es obligatoria',
            'codigo_postal.required' => 'El código postal es obligatorio',
            'codigo_postal.regex' => 'El código postal debe tener 5 dígitos',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe tener mayúsculas, minúsculas y números',
        ]);

        $nombreCompleto = $request->nombre;
        if ($request->apellido1) {
            $nombreCompleto .= ' '.$request->apellido1;
        }
        if ($request->apellido2) {
            $nombreCompleto .= ' '.$request->apellido2;
        }

        $nombreCompleto = ucwords(strtolower($nombreCompleto));

        $direccionFacturacion = $request->mismo_direccion_facturacion
            ? $request->direccion
            : $request->direccion_facturacion;
        $ciudadFacturacion = $request->mismo_direccion_facturacion
            ? $request->ciudad
            : $request->ciudad_facturacion;
        $provinciaFacturacion = $request->mismo_direccion_facturacion
            ? $request->provincia
            : $request->provincia_facturacion;
        $cpFacturacion = $request->mismo_direccion_facturacion
            ? $request->codigo_postal
            : $request->codigo_postal_facturacion;

        $user = User::create([
            'name' => $nombreCompleto,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'provincia' => $request->provincia,
            'codigo_postal' => $request->codigo_postal,
            'direccion_facturacion' => $direccionFacturacion,
            'ciudad_facturacion' => $ciudadFacturacion,
            'provincia_facturacion' => $provinciaFacturacion,
            'codigo_postal_facturacion' => $cpFacturacion,
            'preferencias_combustible' => $request->preferencias_combustible,
            'tipo_conduccion' => $request->tipo_conduccion,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
