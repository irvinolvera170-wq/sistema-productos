@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-sm w-100" style="max-width: 520px;">
        <div class="card-body p-4">
            <h3 class="mb-4">Editar producto</h3>

            <form method="POST" action="{{ route('productos.update', $producto) }}" novalidate>
                @csrf
                @method('PUT')
                @include('productos._form')
                <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
            </form>

            <a href="{{ route('productos.index') }}" class="d-block text-center mt-3">← Volver a productos</a>
        </div>
    </div>
</div>
@endsection