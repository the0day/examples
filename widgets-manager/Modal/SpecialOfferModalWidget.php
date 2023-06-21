<?php

namespace Helper\UI\Widget\Modal;

use Bundles\Account\Entity\UserEntity;
use Bundles\SpecialOffer\Entity\SpecialOfferUserEntity;
use Helper\UI\Widget\Enum\WidgetId;
use Helper\UI\Widget\Enum\WidgetPriority;
use Helper\UI\Widget\Traits\SpecialOfferTrait;
use View;

/**
 * Special offer (modal)
 */
class SpecialOfferModalWidget extends ModalWidget
{
	use SpecialOfferTrait;

	protected string $id = WidgetId::SPECIAL_OFFER_MODAL;
	protected int $priority = WidgetPriority::SPECIAL_OFFER_MODAL;
	private ?SpecialOfferUserEntity $userOffer = null;

	public function __construct(UserEntity $user)
	{
		$this->userOffer = locator()->getSpecialOfferComponent()->getSpecialOfferService()->getSpecialOffer($user);
	}

	protected function getView(): View
	{
		return new View("specialoffer:include/modal.php", ['userOffer' => $this->getOffer()]);
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