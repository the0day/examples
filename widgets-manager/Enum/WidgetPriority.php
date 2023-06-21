<?php

namespace Helper\UI\Widget\Enum;

use Helper\Enum;

/**
 * @method static self SPECIAL_OFFER_FLOATBAR
 * @method static self SPECIAL_OFFER_MODAL
 * @method static self NOTIFICATION
 * @method static self LATEST_NEWS
 */
class WidgetPriority extends Enum
{
	public const SPECIAL_OFFER_MODAL = 1;
	public const NOTIFICATION = 2;
	public const LATEST_NEWS = 3;
	public const SPECIAL_OFFER_FLOAT_BAR = 4;
}