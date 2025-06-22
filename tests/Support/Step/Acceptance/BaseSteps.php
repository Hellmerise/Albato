<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\TimeoutEnum;

class BaseSteps extends AcceptanceTester
{
    protected function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
    }
    
    final protected function safeClick(string $selector): void
    {
        $this->waitVisible($selector);
        $this->click($selector);
    }
    
    final protected function safeFillField(string $field, string $value): void
    {
        $this->waitVisible($field);
        $this->fillField($field, $value);
    }
    
    private function waitVisible(string $element): void
    {
        $this->scrollTo($element);
        $this->waitForElementVisible($element, TimeoutEnum::ELEMENT_VISIBLE->value);
    }
}
