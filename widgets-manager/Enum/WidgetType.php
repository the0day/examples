<?php

namespace Helper\UI\Widget\Enum;

use Helper\Enum;

/**
 * @method static self MODAL
 * @method static self FLOATBAR
 */
class WidgetType extends Enum
{
	public const MODAL = 1;
	public const FLOATBAR = 2;
}