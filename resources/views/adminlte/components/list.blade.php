<{{ $tag ?? 'ul' }}
    @foreach ($wrapper ?? [] as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
>
    @foreach ($list ?? [] as $value)
        <li 
            @foreach ($attr ?? [] as $key => $_value)
                {{ $key }}="{{ $_value }}"
            @endforeach
        >
            @if (is_array($value) && (isset($value['label']) || isset($value['html'])))
                @if (isset($value['wrapper']) && isset($value['wrapper']['tag']))
                    <{{ $value['wrapper']['tag'] }}
                        @foreach ( $value['wrapper']['attrs'] ?? [] as $attr => $attr_value)
                            {{ $attr }}="{{ $attr_value }}"
                        @endforeach
                    >
                @endif
                    @if (isset($value['html']))
                        {!! $value['html'] !!}
                    @else
                        {{ $value['label'] }}
                    @endif
                    @if (isset($value['badge']))
                        <span class="pull-right badge bg-{{ $value['badge']['color'] ?? 'blue' }}">
                            {{ $value['badge']['label'] ?? $value['badge'] }}
                        </span>
                    @endif
                @if (isset($value['wrapper']) && isset($value['wrapper']['tag']))
                    </{{ $value['wrapper']['tag'] }}>
                @endif
            @elseif (!is_array($value))
                {{ $value }}
            @endif
        </li>
    @endforeach
</{{ $tag ?? 'ul' }}>