<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instrument extends Model
{
    protected $table = 'instruments';

    protected $fillable = [
        'marca',
        'modelo',
        'tipo',
        'precio',
        'precio_original',
        'stock',
        'imagen',
        'descripcion',
        'category_id',
        'disponible',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
