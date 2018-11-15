<button type="button" 
    class="btn btn-{{ $type ?? 'block' }} {{ isset($subtype) ? "btn-{$subtype}" : '' }}
    {{ isset($bg) ? "bg-{$bg}" : ''}}">
    {{ $slot }}
</button>