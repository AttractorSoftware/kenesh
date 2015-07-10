<?php

use Behat\MinkExtension\Context\MinkContext;

require 'Driver.php';

class GlobalContext extends MinkContext  {
    public function getDriver() {
        return Driver::getInstance()->getDriver();
    }

    public function elementSetAttribute($cssStringTarget, $attribute, $value) {
        $driver = $this->getDriver();
        $driver->executeScript('$("'. $cssStringTarget .'").attr({ ' . $attribute . ': "' . $value . '"})');
    }

    public function selectSetValue($cssStringSelect, $value) {
        $driver = $this->getDriver();
        $driver->executeScript('$("'. $cssStringSelect .'").val(' . $value . ')');
    }

    public function setVar($variableName, $variableValue) {
        Driver::getInstance()->setVar($variableName, $variableValue);
    }

    public function getVar($variableName) {
        return Driver::getInstance()->getVar($variableName);
    }

    public function findCss($cssString) {
        return Driver::getInstance()->getCurrentPage()->find('css', $cssString);
    }

    public function findAllCss($cssString) {
        return Driver::getInstance()->getCurrentPage()->findAll('css', $cssString);
    }

    public function hasClass($target, $class) {
        return in_array($class, explode(' ', $target->getAttribute('class')));
    }
}