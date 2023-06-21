<?php

namespace Helper\UI\Widget\Floatbar;

use Bundles\Account\Entity\User;
use Bundles\SpecialOffer\Entity\SpecialOfferUserEntity;
use Helper\UI\Widget\Enum\WidgetId;
use Helper\UI\Widget\Enum\WidgetPriority;
use Helper\UI\Widget\Modal\ModalWidget;
use Helper\UI\Widget\Traits\SpecialOfferTrait;
use View;

/**
 * Special offer (floating bar)
 */
class SpecialOfferBarWidget extends ModalWidget
{
	use SpecialOfferTrait;

	protected string $id = WidgetId::SPECIAL_OFFER_FLOATBAR;
	protected int $priority = WidgetPriority::SPECIAL_OFFER_FLOAT_BAR;
	private ?SpecialOfferUserEntity $userOffer = null;

	public function __construct(User $user)
	{
		$this->userOffer = locator()->getSpecialOfferComponent()->getSpecialOfferService()->getSpecialOffer($user);
	}

	protected function getView(): View
	{
		return new View("specialoffer:include/bar.php", ['userOffer' => $this->getOffer()]);
	}

	protected function getCookieSuffix(): string
	{
		return $this->getOffer() ? $this->getOffer()->getId() . date('d') : '';
	}

	public function isActive(): bool
	{
		return !$this->isSpecialOfferPage() && $this->getOffer();
	}

	private function getOffer(): ?SpecialOfferUserEntity
	{
		return $this->userOffer;
	}
}