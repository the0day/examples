<?php

namespace Helper\UI\Widget\Floatbar;

use Exception;
use Helper\UI\Widget\AbstractWidget;
use Helper\UI\Widget\Enum\WidgetType;
use View;

class FloatBarWidget extends AbstractWidget
{
	protected int $widgetType = WidgetType::FLOATBAR;

	public function isActive(): bool
	{
		return false;
	}

	protected function getView(): View
	{
		throw new Exception("Invalid view layout for float bar widget");
	}
}