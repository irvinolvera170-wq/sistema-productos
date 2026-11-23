@props(['tipo' => 'success'])

@if (session('status'))
    <div class="alert alert-{{ $tipo }}">{{ session('status') }}</div>
@endif