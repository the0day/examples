<?php

$width = $active / (count($items) - 1) * 100;
?>
<div class="mt-6" aria-hidden="true">
    <div class="bg-gray-200 rounded-full overflow-hidden">
        <div class="h-2 bg-green-400 rounded-full"
             style="width: {{$width}}%"></div>
    </div>
    <div class="hidden sm:grid grid-cols-5 text-sm font-medium text-gray-600 mt-3">
        @foreach($items as $i => $label)
            <?php
            $class = $active - 1 == $i ? 'text-indigo-600' : '';

            if (count($items) - 1 == $i) {
                $alignClass = 'text-right';
            } else if ($i == 0) {
                $alignClass = 'text-left';
            } else {
                $alignClass = 'text-center';
            }

            if ($active > $i) {
                $colorClass = 'text-green-500';
            } else if ($active < $i) {
                $colorClass = 'text-success-500';
            } else {
                $colorClass = 'text-indigo-600 font-bold';
            }
            ?>
            <div class="{{$colorClass}} {{$alignClass}}">@lang($label)</div>
        @endforeach
    </div>
</div>
