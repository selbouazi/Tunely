<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'telefono' => 'nullable|string|regex:/^(\+34)?[0-9]{9}$/',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|regex:/^[0-9]{5}$/',
            'direccion_facturacion' => 'nullable|string|max:255',
            'ciudad_facturacion' => 'nullable|string|max:100',
            'provincia_facturacion' => 'nullable|string|max:100',
            'codigo_postal_facturacion' => 'nullable|string|regex:/^[0-9]{5}$/',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'instrumento_preferido' => 'nullable|string|max:50',
            'nivel_experiencia' => 'nullable|string|max:50',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}
