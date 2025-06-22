<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use Tests\Support\AcceptanceTester;
use Tests\Support\Config\TimeoutEnum;
use Tests\Support\Page\Interfaces\PageInterface;

abstract class AbstractPage implements PageInterface
{
    protected static AcceptanceTester $acceptanceTester;
    
    abstract protected function returnElementsForWait(): array;
    
    final public function waitForPageVisible(): void
    {
        foreach ($this->returnElementsForWait() as $element) {
            static::$acceptanceTester->waitForElementVisible($element, TimeoutEnum::ELEMENT_VISIBLE->value);
        }
    }
    
    final public function waitForPageNotVisible(): void
    {
        foreach ($this->returnElementsForWait() as $element) {
            static::$acceptanceTester->waitForElementNotVisible($element, TimeoutEnum::ELEMENT_VISIBLE->value);
        }
    }
    
    final protected function getErrorMessageOrFail(string $cssOrXPath): string
    {
        if (static::$acceptanceTester->tryToSeeElement($cssOrXPath)) {
            return static::$acceptanceTester->grabTextFrom($cssOrXPath);
        } else {
            return static::$acceptanceTester->fail("Элемент '$cssOrXPath' не найден на странице.");
        }
    }
}