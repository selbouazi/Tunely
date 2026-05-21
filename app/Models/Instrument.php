<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory;

    protected $table = 'instruments';

    protected $appends = ['precio_sin_iva', 'precio_con_iva'];

    protected $fillable = [
        'marca',
        'modelo',
        'tipo',
        'precio',
        'precio_original',
        'iva',
        'stock',
        'imagen',
        'descripcion',
        'category_id',
        'subcategory_id',
        'disponible',
        'descuento_general_applied',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function getPrecioSinIvaAttribute(): float
    {
        return round($this->precio / (1 + $this->iva / 100), 2);
    }

    public function getPrecioConIvaAttribute(): float
    {
        return round($this->precio, 2);
    }
}
