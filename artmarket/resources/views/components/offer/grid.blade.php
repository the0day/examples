<?php
/**
 * @var Offer[] $items
 */

use App\Models\Offer;

?>
<div>
    @foreach($items as $item)
        <x-offer.grid-item :item="$item"/>
    @endforeach
</div>
