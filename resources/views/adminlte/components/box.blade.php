<div class="box box-{{ $type ?? 'primary' }}">
        <div class="box-header with-border">
            @if (isset($title) && !is_null($title) && !empty($title))
                <h3 class="box-title">{{ $title }}</h3>
            @endif
            @isset ($interaction)
                @if ($interaction === 'collapsable')
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            @faicon('minus')
                        </button>
                    </div>
                @elseif ($interaction === 'closable')
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            @faicon('times')
                        </button>
                    </div>
                @elseif ($interaction === 'all')
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            @faicon('minus')
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            @faicon('times')
                        </button>
                    </div>
                @endif
            @endisset
        </div>
    <div class="box-body">
        {{ $slot }}
    </div>
    @if (isset($footer) && !is_nul($footer) && !empty($footer))
        <div class="box-footer">
            {{ $footer }}
        </div>
    @endif
</div>