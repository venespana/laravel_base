<div class="grid-row-actions">
    @foreach($links as $link)
        <a
            href="{{ $link['href'] ?? '#' }}"
            target="{{ $link['target'] }}"
            class="{{ $link['class'] }}"
            @if (array_key_exists('modal', $link))
                @foreach ($link['modal'] as $key => $value)
                    @if (in_array($key, ['toggle', 'target']))
                        data-{{ $key }}="{{ $value }}"
                    @endif
                @endforeach
            @endif
        ></a>
        @if (array_key_exists('modal', $link))
            <!-- Modal -->
            <div
                class="modal fade" id="{{ str_replace('#', '', $link['modal']['target']) }}" tabindex="-1" role="dialog"
                aria-hidden="true"
            >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $link['modal']['label'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ $link['modal']['body'] }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        {{ $link['modal']['cancel'] }}
                    </button>
                    <a href="{{ $link['modal']['link'] }}" class="btn btn-success">{{ $link['modal']['success'] }}</a>
                </div>
                </div>
            </div>
            </div>
        @endif
    @endforeach
</div>