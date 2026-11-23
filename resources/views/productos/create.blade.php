@extends('layouts.app')

@section('title', 'Registrar producto')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-sm w-100" style="max-width: 520px;">
        <div class="card-body p-4">
            <h3 class="mb-4">Registrar producto</h3>

            <form method="POST" action="{{ route('productos.store') }}" novalidate>
                @csrf
                @include('productos._form')
                <button type="submit" class="btn btn-primary w-100">Registrar</button>
            </form>

            <a href="{{ route('productos.index') }}" class="d-block text-center mt-3">← Volver a productos</a>
        </div>
    </div>
</div>
@endsection