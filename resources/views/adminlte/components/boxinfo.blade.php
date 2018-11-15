<div class="info-box">
    <span class="info-box-icon bg-{{ $color ?? 'aqua-active' }}">
        @faicon({{ $icon }})
    </span>
    <div class="info-box-content">
        <span class="info-box-text">{{ $title ?? '' }}</span>
        <span class="info-box-number">{{ $slot ?? '' }}</span>
    </div>
</div>