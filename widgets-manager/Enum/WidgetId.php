<?php

namespace Helper\UI\Widget\Enum;

use Helper\Enum;

/**
 * @method static self SPECIAL_OFFER_FLOATBAR
 * @method static self SPECIAL_OFFER_MODAL
 * @method static self NOTIFICATION
 * @method static self LATEST_NEWS
 */
class WidgetId extends Enum
{
	public const SPECIAL_OFFER_FLOATBAR = 'widget_offer_bar';
	public const SPECIAL_OFFER_MODAL = 'widget_offer_modal';
	public const NOTIFICATION = 'widget_notifications';
	public const LATEST_NEWS = 'widget_latest_news';
}