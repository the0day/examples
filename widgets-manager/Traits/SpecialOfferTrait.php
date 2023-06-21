<?php

namespace Helper\UI\Widget\Traits;

use Helper\UI\Widget\Floatbar\SpecialOfferBarWidget;
use Helper\UI\Widget\Modal\SpecialOfferModalWidget;

/**
 * @mixin SpecialOfferModalWidget
 * @mixin SpecialOfferBarWidget
 */
trait SpecialOfferTrait
{
	private function isSpecialOfferPage(): bool
	{
		$uri = $_SERVER['REQUEST_URI'];

		return preg_match('/\/special-offer/ui', $uri);
	}
}