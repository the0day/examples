<?php

namespace Helper\UI\Widget;

use Cookie;
use View;

/**
 * The AbstractWidget class represents a base class for widgets.
 * Template Pattern
 */
abstract class AbstractWidget
{
    protected string $id;
    protected string $layout;
    protected array $data = [];
    protected int $priority;
    protected int $widgetType;

    /**
     * Get the view for the widget.
     *
     * @return View The view instance.
     */
    abstract protected function getView(): View;

    /**
     * Check if the widget is closed.
     *
     * @return bool True if the widget is closed, false otherwise.
     */
    public function isClosed(): bool
    {
        return Cookie::get($this->getCookieKey(), false);
    }

    /**
     * Get the cookie key for the widget.
     *
     * @return string The cookie key.
     */
    public function getCookieKey(): string
    {
        return $this->getId() . $this->getCookieSuffix();
    }

    /**
     * Get the cookie suffix for the widget.
     *
     * @return string The cookie suffix.
     */
    protected function getCookieSuffix(): string
    {
        return '';
    }

    /**
     * Get the ID of the widget.
     *
     * @return string The widget ID.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Render the widget.
     *
     * @return string The rendered output of the widget.
     */
    public function view(): string
    {
        return $this->getView()
            ->addVar('key', $this->getCookieKey())
            ->parse();
    }

    /**
     * Check if the widget is active.
     *
     * @return bool True if the widget is active, false otherwise.
     */
    public function isActive(): bool
    {
        return false;
    }

    /**
     * Get the priority of the widget.
     *
     * @return int The widget priority.
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Get the data for the widget.
     *
     * @return array The widget data.
     */
    protected function getData(): array
    {
        return $this->data;
    }
}