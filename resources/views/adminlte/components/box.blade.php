<div class="box box-{{ $type ?? 'primary' }}">
    @if (isset($title) && !is_null($title) && !empty($title))
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>
        </div>
    @endif
    <div class="box-body">
        {{ $slot }}
    </div>
    @if (isset($footer) && !is_nul($footer) && !empty($footer))
        <div class="box-footer">
            {{ $footer }}
        </div>
    @endif
</div>