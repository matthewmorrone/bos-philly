<?php
function icon($name, $width = 20, $height = 20) {
    $w = intval($width);
    $h = intval($height);
    return "<svg width=\"{$w}\" height=\"{$h}\" aria-hidden=\"true\"><use href=\"#icon-{$name}\"/></svg>";
}
?>
