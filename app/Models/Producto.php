<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nombre', 'descripcion', 'categoria_id', 'precio', 'existencias', 'estado'])]
class Producto extends Model
{
    public const ESTADOS = [
        'disponible' => 'Disponible',
        'agotado' => 'Agotado',
    ];

    protected function casts(): array
    {
        return ['precio' => 'decimal:2'];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // Cada filtro se agrega solo si el usuario lo llenó, así se combinan dinámicamente
    public function scopeFiltrar(Builder $query, array $f): Builder
    {
        return $query
            ->when(isset($f['buscar']), fn ($q) => $q->where('nombre', 'like', '%'.$f['buscar'].'%'))
            ->when(isset($f['categoria']), fn ($q) => $q->where('categoria_id', $f['categoria']))
            ->when(isset($f['precio_min']), fn ($q) => $q->where('precio', '>=', $f['precio_min']))
            ->when(isset($f['precio_max']), fn ($q) => $q->where('precio', '<=', $f['precio_max']))
            ->when(isset($f['estado']), fn ($q) => $q->where('estado', $f['estado']));
    }
}