@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'Kibo')
<img src="{{ asset('logo/green.png') }}" class="logo" alt="Kibo Logo" style="height: 50px; width: auto;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
