<?php

use Behat\Mink\Session;

class Driver {
    private static $instance;
    private $driver;
    private $page;
    private $variables = array();
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getDriver()
    {
        if (!$this->driver) {
            $this->driver = new \Behat\Mink\Driver\Selenium2Driver();
            $this->driver->start();
        }
        return $this->driver;
    }
    public function openPage($url)
    {
        $session = new Session($this->getDriver());
        $session->start();
        $session->visit($url);
        $this->page = $session->getPage();
    }
    public function getCurrentPage()
    {
        return $this->page;
    }
    public function closeBrowser()
    {
        $this->driver->stop();
    }
    public function getVar($variableName)
    {
        return isset($this->variables[$variableName]) ? $this->variables[$variableName] : 'Variable not defined';
    }
    public function setVar($variableName, $variableValue)
    {
        $this->variables[$variableName] = $variableValue;
    }
    public function getSession(){
    }
}