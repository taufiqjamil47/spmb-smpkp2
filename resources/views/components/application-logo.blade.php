@php
    $logoPath = public_path('images/Logo.png');
@endphp

@if (file_exists($logoPath))
    <img src="{{ asset('images/Logo.png') }}" alt="Logo" style="width: 25rem; height: auto;"
        {{ $attributes->merge(['class' => '']) }}>
@else
    <!-- fallback jika file tidak ditemukan -->
    <div
        style="width: 25rem; height: 25rem; background: #ccc; display: flex; align-items: center; justify-content: center;">
        <span style="font-size: 12px;">Logo</span>
    </div>
@endif
