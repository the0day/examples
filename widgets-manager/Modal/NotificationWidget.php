<?php

namespace Helper\UI\Widget\Modal;

use Bundles\Notification\Entity\NotificationEntity;
use Cookie;
use Helper\UI\Widget\Enum\WidgetId;
use Helper\UI\Widget\Enum\WidgetPriority;
use View;

/**
 * Notifications (modal)
 */
class NotificationWidget extends ModalWidget
{
	protected string $id = WidgetId::NOTIFICATION;
	protected int $priority = WidgetPriority::NOTIFICATION;
	/** @var array|NotificationEntity[] */
	private array $notifications;
	private ?NotificationEntity $lastNotification = null;

	public function __construct(array $notifications)
	{
		$this->notifications = $notifications;
	}

	/**
	 * @return NotificationEntity[]
	 */
	public function getNotifications(): array
	{
		return $this->notifications ?? [];
	}

	public function getLastNotification(): ?NotificationEntity
	{
		if ($this->lastNotification !== null) {
			return $this->lastNotification;
		}

		foreach ($this->getNotifications() as $notification) {
			if (!$notification->getPromoItems() && !$notification->isShowWithoutItems()) {
				continue;
			}

			return $this->lastNotification = $notification;
		}

		return null;
	}

	protected function getView(): View
	{
		$this->setNotificationSawAt(time());

		return new View("notification:include/notification_float_bar.php", ['item' => $this->getLastNotification()]);
	}

	public function isActive(): bool
	{
		if (!$this->isTimeToShow()) {
			return false;
		}

		return $this->getLastNotification() !== null;
	}

	protected function getCookieSuffix(): string
	{
		return $this->getLastNotification() ? $this->getLastNotification()->getId() : '';
	}

    /** CODE BELOW IS LEGACY */
	private function isTimeToShow(): bool
	{
		return abs(time() - $this->getNotificationSawAt()) > $this->getNotificationPause();
	}

	private function getNotificationSawAt(): int
	{
		return Cookie::get($this->getNotificationSawAtCookieKey(), 0);
	}

	private function setNotificationSawAt(int $time): void
	{
		Cookie::set($this->getNotificationSawAtCookieKey(), $time, 86400 * 30);
	}

	private function getNotificationSawAtCookieKey(): string
	{
		return 'ntfy_saw_at';
	}

	private function getNotificationPause(): int
	{
		return \Settings::get('city.notification_pause', 0);
	}
}