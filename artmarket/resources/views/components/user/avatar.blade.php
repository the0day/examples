<?php
if (!isset($size)) {
    $size = 10;
}

switch ($size) {
    case "large":
        $size = "h-40 w-40";
        break;

    case "small":
        $size = "h-10 w-10";
        break;

    default:
        $size = "h-$size w-$size";
        break;
}
?>

<img {{ $attributes->class([$size])->merge(['class' => 'rounded-full']) }}/>
