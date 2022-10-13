<?php
if (isset($size)) {
    switch ($size) {
        case "xs":
            $padding = "px-0 py-0";
            break;

        default:

            break;
    }
}
$btnColor = $btnColor ?? "indigo";

$padding = !isset($padding) ? "px-4 py-2" : $padding;
$class = [
    $padding,
    "font-medium text-base text-white",
    "bg-$btnColor-600 hover:bg-$btnColor-700 active:bg-$btnColor-800",
    "border border-transparent rounded-md focus:outline-none",
    "disabled:opacity-25 transition ease-in-out duration-150",
    "shadow-md active:shadow-none focus:shadow-none"
];
?>


<button {{ $attributes->merge(['type' => 'submit', 'class' => implode(" ", $class)]) }}>
    {{ $slot }}
</button>
