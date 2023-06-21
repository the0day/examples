<?php

namespace Helper\UI\Widget\Modal;

use Exception;
use Helper\UI\Widget\AbstractWidget;
use Helper\UI\Widget\Enum\WidgetType;
use View;

class ModalWidget extends AbstractWidget
{
	protected int $widgetType = WidgetType::MODAL;

	public function isActive(): bool
	{
		return false;
	}

	protected function getView(): View
	{
		throw new Exception("Invalid view layout for modal widget");
	}
}