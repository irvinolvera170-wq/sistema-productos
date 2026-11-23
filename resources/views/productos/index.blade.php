@extends('layouts.app')

@section('title', 'Productos')

@section('content')
@php $esAdmin = auth()->user()->esAdmin(); @endphp

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Catálogo de productos</h3>
                @if ($esAdmin)
                    <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">+ Nuevo producto</a>
                @endif
            </div>

            <x-alerta />

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Filtros: se envían por GET para que queden en la URL --}}
            <form method="GET" action="{{ route('productos.index') }}" class="row g-2 mb-4" novalidate>
                <div class="col-md-4">
                    <label class="form-label small mb-0">Buscar por nombre</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control"
                           placeholder="Ej. Laptop">
                </div>

                <div class="col-md-4">
                    <label class="form-label small mb-0">Categoría</label>
                    <select name="categoria" class="form-select">
                        <option value="">Todas</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}" @selected(request('categoria') == $c->id)>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small mb-0">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        @foreach (\App\Models\Producto::ESTADOS as $valor => $texto)
                            <option value="{{ $valor }}" @selected(request('estado') === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-0">Precio mínimo</label>
                    <input type="number" name="precio_min" min="0" step="0.01"
                           value="{{ request('precio_min') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-0">Precio máximo</label>
                    <input type="number" name="precio_max" min="0" step="0.01"
                           value="{{ request('precio_max') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label small mb-0">Ordenar por</label>
                    <select name="orden" class="form-select">
                        @foreach ($ordenes as $clave => $o)
                            <option value="{{ $clave }}" @selected(request('orden', 'fecha_desc') === $clave)>{{ $o[0] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-0">Por página</label>
                    <select name="por_pagina" class="form-select" onchange="this.form.submit()">
                        @foreach ($porPagina as $n)
                            <option value="{{ $n }}" @selected((int) request('por_pagina', 10) === $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Existencias</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            @if ($esAdmin) <th class="text-end">Acciones</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ str($p->descripcion)->limit(60) }}</td>
                                <td>{{ $p->categoria->nombre }}</td>
                                <td>${{ number_format($p->precio, 2) }}</td>
                                <td>{{ $p->existencias }}</td>
                                <td><x-estado-badge :estado="$p->estado" /></td>
                                <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                @if ($esAdmin)
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('productos.edit', $p) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                            <form method="POST" action="{{ route('productos.destroy', $p) }}"
                                                  onsubmit="return confirm('¿Eliminar este producto? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $esAdmin ? 9 : 8 }}" class="text-center text-muted">
                                    No se encontraron productos con esos filtros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    @if ($productos->total() > 0)
                        Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }}
                        de {{ $productos->total() }} productos encontrados
                        · Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
                    @else
                        0 productos encontrados
                    @endif
                </p>
                {{ $productos->links() }}
            </div>

        </div>
    </div>
</div>
@endsection