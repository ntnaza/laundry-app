@props(['url'])
@php
    $setting = \App\Models\Setting::first();
    $appName = $setting->shop_name ?? config('app.name');
@endphp
<tr>
<td class="header" style="text-align: center; padding: 25px 0;">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
    @if($setting && $setting->logo)
        {{-- Gunakan Teks Profesional saja untuk Email karena gambar lokal sering tidak terbaca di email client --}}
        <div style="color: #2563EB; font-size: 28px; font-weight: 800; font-family: 'Outfit', Helvetica, Arial, sans-serif; letter-spacing: -0.5px;">
            {{ $appName }}
        </div>
    @else
        <div style="color: #2563EB; font-size: 28px; font-weight: 800; font-family: 'Outfit', Helvetica, Arial, sans-serif; letter-spacing: -0.5px;">
            {{ $appName }}
        </div>
    @endif
</a>
</td>
</tr>
