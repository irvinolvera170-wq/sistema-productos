<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    // clave => [texto a mostrar, columna, dirección]
    public const ORDENES = [
        'fecha_desc'       => ['Fecha: más recientes', 'created_at', 'desc'],
        'fecha_asc'        => ['Fecha: más antiguos', 'created_at', 'asc'],
        'nombre_asc'       => ['Nombre: A-Z', 'nombre', 'asc'],
        'nombre_desc'      => ['Nombre: Z-A', 'nombre', 'desc'],
        'precio_desc'      => ['Precio: mayor a menor', 'precio', 'desc'],
        'precio_asc'       => ['Precio: menor a mayor', 'precio', 'asc'],
        'existencias_desc' => ['Existencias: mayor a menor', 'existencias', 'desc'],
        'existencias_asc'  => ['Existencias: menor a mayor', 'existencias', 'asc'],
    ];

    public const POR_PAGINA = [10, 15, 25, 50];

    public function index(Request $request)
    {
        $f = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'integer', 'exists:categorias,id'],
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0',
                Rule::when($request->filled('precio_min'), ['gte:precio_min'])],
            'estado' => ['nullable', Rule::in(array_keys(Producto::ESTADOS))],
            'orden' => ['nullable', Rule::in(array_keys(self::ORDENES))],
            'por_pagina' => ['nullable', Rule::in(self::POR_PAGINA)],
        ], [
            'categoria.exists' => 'La categoría seleccionada no existe.',
            'precio_min.numeric' => 'El precio mínimo debe ser un número.',
            'precio_max.numeric' => 'El precio máximo debe ser un número.',
            'precio_max.gte' => 'El precio máximo no puede ser menor que el mínimo.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'orden.in' => 'El criterio de orden no es válido.',
            'por_pagina.in' => 'La cantidad por página debe ser 10, 15, 25 o 50.',
        ]);

        [, $columna, $direccion] = self::ORDENES[$f['orden'] ?? 'fecha_desc'];

        $productos = Producto::with('categoria')          // evita consultas N+1
            ->filtrar($f)
            ->orderBy($columna, $direccion)
            ->orderBy('id')                               // desempate: páginas estables
            ->paginate((int) ($f['por_pagina'] ?? 10))
            ->withQueryString();                          // conserva filtros y orden

        return view('productos.index', [
            'productos' => $productos,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'ordenes' => self::ORDENES,
            'porPagina' => self::POR_PAGINA,
        ]);
    }

    public function create()
    {
        return view('productos.create', [
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')
            ->with('status', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('status', 'Producto eliminado correctamente.');
    }
}