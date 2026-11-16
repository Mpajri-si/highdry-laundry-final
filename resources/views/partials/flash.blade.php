<div class="container mt-3">
    @if (session('success')) <div class="alert alert-success">{{
session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error')
}}</div> @endif
</div>

components/status-badge.blade.php
@props(['status'])
@php
        $color = match($status) {
        'Menunggu Verifikasi' => 'secondary',
        'Diproses' => 'primary', 'Dicuci' => 'info',
        'Disetrika' => 'warning', 'Selesai' => 'success',
        default => 'danger',
    };
@endphp
<span class="badge text-bg-{{ $color }}">{{ $status }}</span>
