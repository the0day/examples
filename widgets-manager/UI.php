<?php

namespace Helper\UI\Widget;

use Exception;

/**
 * The UI class provides access to the widget system
 * Singleton Pattern
 */
class UI
{
    private static UI $instance;
    private Widgets $widgets;

    /**
     * Prevent unserialization of the object.
     *
     * @throws Exception Unavailable for this object.
     */
    public function __wakeup()
    {
        throw new Exception("Unavailable for this object");
    }

    /**
     * Prevent cloning of the object.
     *
     * @throws Exception Unavailable for this object.
     */
    public function __clone()
    {
        throw new Exception('Unavailable for this object');
    }

    /**
     * Get the instance of the UI class.
     *
     * @return UI The instance of the UI class.
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->widgets = new Widgets();
        }

        return self::$instance;
    }

    /**
     * Get the Widgets instance.
     *
     * @return Widgets The Widgets instance.
     */
    public function widgets(): Widgets
    {
        return $this->widgets;
    }
}
