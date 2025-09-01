<?php
function icon($name, $width = 20, $height = 20) {
    $w = intval($width);
    $h = intval($height);
    return "<svg class=\"icon\" width=\"{$w}\" height=\"{$h}\" aria-hidden=\"true\" fill=\"currentColor\"><use href=\"#icon-{$name}\"/></svg>";
}
?>
