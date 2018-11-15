<?php

Blade::directive('faicon', function ($expresion) {
    list($icon, $type) = array_pad(explode(', ', $expresion), 2, 'fas');
    
    $icon = str_replace_last("'", '', str_replace_first("'", '', $icon));
    $type = str_replace_last("'", '', str_replace_first("'", '', $type));

    return "<i class=\"{$type} fa-fw fa-{$icon}\"></i>";
});

Blade::directive('glicon', function ($icon) {
    $icon = str_replace_last("'", '', str_replace_first("'", '', $icon));

    return "<i class=\"glyphicon glyphicon-{$icon}\"></i>";
});
