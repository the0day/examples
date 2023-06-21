<?php

namespace Helper\UI\Widget;

/**
 * The Widgets class represents a collection of widgets.
 * Composite Pattern
 */
class Widgets
{
    private array $widgets = [];
    private array $ordersMap = [];
    private array $alwaysRender = [];
    private array $closedDependencies = [];
    private array $renderWith = [];

    /**
     * Add a widget to the collection.
     *
     * @param AbstractWidget $widget The widget to add.
     * @return self
     */
    public function add(AbstractWidget $widget): self
    {
        $this->widgets[$widget->getId()] = $widget;
        $this->ordersMap[$widget->getId()] = $widget->getPriority();

        return $this;
    }

    /**
     * Get a widget by its ID.
     *
     * @param string $id The ID of the widget.
     * @return AbstractWidget|null The widget instance, or null if not found.
     */
    public function get(string $id): ?AbstractWidget
    {
        return $this->all()[$id] ?? null;
    }

    /**
     * Get all the widgets in the collection.
     *
     * @return array An array of all the widgets.
     */
    public function all(): array
    {
        return $this->widgets;
    }

    /**
     * Check if a widget with the given ID exists in the collection.
     *
     * @param string $id The ID of the widget.
     * @return bool True if the widget exists, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->all()[$id]);
    }

    /**
     * Get the first widget in the collection based on priority.
     *
     * @return AbstractWidget|null The first widget instance, or null if the collection is empty.
     */
    public function first(): ?AbstractWidget
    {
        $items = $this->all();
        $key = array_search(min($this->ordersMap), $this->ordersMap);

        return $items[$key];
    }

    /**
     * Get only the specified widgets by their IDs.
     *
     * @param array $ids An array of widget IDs to include.
     * @return AbstractWidget[] An array of the specified widgets.
     */
    public function only(array $ids): array
    {
        return array_intersect_key($this->all(), array_flip($ids));
    }

    /**
     * Exclude the specified widgets by their IDs.
     *
     * @param array $ids An array of widget IDs to exclude.
     * @return AbstractWidget[] An array of the remaining widgets.
     */
    public function exclude(array $ids): array
    {
        return array_diff($this->all(), array_flip($ids));
    }

    /**
     * Render the widgets.
     *
     * @param int $limit The maximum number of widgets to render (0 means no limit).
     * @return string The rendered output of the widgets.
     */
    public function render(int $limit = 0): string
    {
        $ordersMap = $this->ordersMap;
        asort($ordersMap);

        $widgets = $this->all();
        $selected = 0;
        $render = [];
        foreach ($ordersMap as $widgetId => $priority) {
            $widget = $widgets[$widgetId];
            $isWith = array_intersect($this->getRenderWith($widgetId), array_keys($render));
            if ($selected >= $limit && !in_array($widgetId, $this->alwaysRender) && !$isWith) {
                continue;
            }

            if (!$this->checkClosedDependencies($widgetId)) {
                continue;
            }

            if ($widget->isClosed() || !$widget->isActive()) {
                continue;
            }

            $render[$widgetId] = $widget->view();

            $selected++;
        }

        return implode("\n", $render);
    }

    /**
     * Render a single widget.
     *
     * @return string The rendered output of the widget.
     */
    public function renderOne(): string
    {
        return $this->render(1);
    }

    /**
     * Mark one or more widgets to always be rendered, regardless of the limit.
     *
     * @param mixed ...$ids The IDs of the widgets to always render.
     * @return self
     */
    public function always(...$ids): self
    {
        $this->alwaysRender = array_merge($this->alwaysRender, $ids);

        return $this;
    }

    /**
     * Set the closed dependencies for a widget.
     *
     * @param string $render The ID of the widget to render.
     * @param mixed ...$dependencies The IDs of the dependencies.
     * @return self
     */
    public function renderWhenClosed(string $render, ...$dependencies): self
    {
        if (isset($this->closedDependencies[$render])) {
            $dependencies = array_merge($this->closedDependencies[$render], $dependencies);
        }

        $this->closedDependencies[$render] = $dependencies;

        return $this;
    }

    /**
     * Set the widgets to be rendered with a specific widget.
     *
     * @param string $widgetId The ID of the widget to render with.
     * @param mixed ...$with The IDs of the widgets to render with.
     * @return self
     */
    public function renderWith(string $widgetId, ...$with): self
    {
        if (isset($this->renderWith[$widgetId])) {
            $with = array_merge($this->renderWith[$widgetId], $with);
        }

        $this->renderWith[$widgetId] = $with;

        return $this;
    }

    /**
     * Get the widgets that are rendered with a specific widget.
     *
     * @param string $widgetId The ID of the widget.
     * @return array An array of widget IDs.
     */
    public function getRenderWith(string $widgetId): array
    {
        return $this->renderWith[$widgetId] ?? [];
    }

    /**
     * Get the closed dependencies for a widget.
     *
     * @param string $widgetId The ID of the widget.
     * @return array An array of widget IDs that are closed dependencies.
     */
    private function getClosedDependencies(string $widgetId): array
    {
        return $this->closedDependencies[$widgetId] ?? [];
    }

    /**
     * Check if the closed dependencies for a widget are closed.
     *
     * @param string $widgetId The ID of the widget.
     * @return bool True if all the closed dependencies are closed, false otherwise.
     */
    private function checkClosedDependencies(string $widgetId): bool
    {
        if (!$this->getClosedDependencies($widgetId)) {
            return true;
        }

        return $this->areClosed(...$this->closedDependencies[$widgetId]);
    }

    /**
     * Check if the specified widgets are closed.
     *
     * @param mixed ...$widgetIds The IDs of the widgets to check.
     * @return bool True if all the specified widgets are closed, false otherwise.
     */
    private function areClosed(...$widgetIds): bool
    {
        foreach ($widgetIds as $widgetId) {
            if (!$widget = $this->get($widgetId)) {
                return false;
            }

            if (!$widget->isClosed()) {
                return false;
            }
        }

        return true;
    }
}