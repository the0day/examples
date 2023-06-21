<?php

namespace Helper\UI\Widget;

use Helper\UI\Widget\Modal\ModalWidget;
use View;

/**
 * Null Widget
 */
class NullWidget extends ModalWidget
{
	protected string $id = 'null';
	protected int $priority = 0;

	public function __construct()
	{

	}

	protected function getView(): View
	{
		return new View("", []);
	}

	public function isActive(): bool
	{
		return true;
	}
}