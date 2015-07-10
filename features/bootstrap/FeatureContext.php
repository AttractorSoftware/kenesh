<?php

use PHPUnit_Framework_Assert as Assert;

require 'GlobalContext.php';
/**
 * Defines application features from the specific context.
 */
class FeatureContext extends GlobalContext {
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given /^Step (\d+)$/
     */
    public function step($arg1)
    {
        $driver = $this->getDriver();
        $driver->visit('http://google.com');
    }
}
