<?php

namespace Helper\UI\Widget\Modal;

use Bundles\TextContent\Entity\NewsEntity;
use Helper\UI\Widget\Enum\WidgetId;
use Helper\UI\Widget\Enum\WidgetPriority;
use View;

/**
 * Last news (modal)
 */
class LatestNewsWidget extends ModalWidget
{
	protected string $id = WidgetId::LATEST_NEWS;
	protected int $priority = WidgetPriority::LATEST_NEWS;
	private ?NewsEntity $latestNews = null;

	public function __construct()
	{
		$this->latestNews = locator()->getTextContentComponent()->getNewsService()->getLatestNews();
	}

	protected function getView(): View
	{
		return new View("textcontent:include/news_float_bar.php", ['news' => $this->getNewsEntity()]);
	}

	public function isActive(): bool
	{
		return $this->getNewsEntity() && !$this->isClosed();
	}

	private function getNewsEntity(): ?NewsEntity
	{
		return $this->latestNews;
	}

	protected function getCookieSuffix(): string
	{
		return $this->getNewsEntity() ? $this->latestNews->getId() : '';
	}
}