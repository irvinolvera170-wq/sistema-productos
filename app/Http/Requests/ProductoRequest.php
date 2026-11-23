<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Segunda barrera además del middleware: solo un admin puede enviar este formulario
        return $this->user()?->esAdmin() ?? false;
    }

    public function rules(): array
    {
        $producto = $this->route('producto');   // null al registrar

        return [
            'nombre' => ['required', 'string', 'max:255',
                Rule::unique('productos', 'nombre')->ignore($producto?->id)],
            'descripcion' => ['required', 'string', 'max:1000'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'precio' => ['required', 'numeric', 'gt:0', 'max:99999999.99', 'decimal:0,2'],
            'existencias' => ['required', 'integer', 'min:0', 'max:1000000'],
            'estado' => ['required', Rule::in(array_keys(Producto::ESTADOS))],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Escribe el nombre del producto.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'descripcion.required' => 'Escribe la descripción del producto.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'categoria_id.required' => 'Selecciona una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'precio.required' => 'Escribe el precio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.gt' => 'El precio debe ser mayor que cero.',
            'precio.decimal' => 'El precio admite máximo 2 decimales.',
            'precio.max' => 'El precio es demasiado alto.',
            'existencias.required' => 'Escribe las existencias.',
            'existencias.integer' => 'Las existencias deben ser un número entero.',
            'existencias.min' => 'Las existencias no pueden ser negativas.',
            'estado.required' => 'Selecciona el estado.',
            'estado.in' => 'El estado debe ser Disponible o Agotado.',
        ];
    }
}