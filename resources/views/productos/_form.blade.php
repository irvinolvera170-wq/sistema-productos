@php $editando = isset($producto); @endphp

<div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}"
           class="form-control @error('nombre') is-invalid @enderror">
    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" rows="3"
              class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Categoría</label>
    <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror">
        <option value="">Selecciona una categoría</option>
        @foreach ($categorias as $c)
            <option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id ?? '') == $c->id)>
                {{ $c->nombre }}
            </option>
        @endforeach
    </select>
    @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-6 mb-3">
        <label class="form-label">Precio</label>
        <input type="number" name="precio" min="0.01" step="0.01"
               value="{{ old('precio', $producto->precio ?? '') }}"
               class="form-control @error('precio') is-invalid @enderror">
        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-6 mb-3">
        <label class="form-label">Existencias</label>
        <input type="number" name="existencias" min="0" step="1"
               value="{{ old('existencias', $producto->existencias ?? 0) }}"
               class="form-control @error('existencias') is-invalid @enderror">
        @error('existencias') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select @error('estado') is-invalid @enderror">
        @foreach (\App\Models\Producto::ESTADOS as $valor => $texto)
            <option value="{{ $valor }}" @selected(old('estado', $producto->estado ?? 'disponible') === $valor)>
                {{ $texto }}
            </option>
        @endforeach
    </select>
    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>