<div class="info-box {{ isset($bg) ? "bg-{$bg}" : ''}}">
    <span class="info-box-icon bg-{{ $color ?? 'aqua-active' }}">
        @faicon({{ $icon }})
    </span>
    <div class="info-box-content">
        <span class="info-box-text">{{ $title ?? '' }}</span>
        <span class="info-box-number">{{ $slot ?? '' }}</span>
        @isset($progress)
            <div class="progress">
                <div class="progress-bar" style="width: {{ $progress }}%"></div>
            </div>
            <span class="progress-description">{{ $desc ?? '' }}</span>
        @endisset
    </div>
</div>