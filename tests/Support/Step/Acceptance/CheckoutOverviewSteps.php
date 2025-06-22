<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;

use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\CheckoutOverviewPage;

class CheckoutOverviewSteps extends CartSteps
{
    private readonly CheckoutOverviewPage $checkoutOverviewPage;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario, $acceptanceTester);
        $this->checkoutOverviewPage = new CheckoutOverviewPage($acceptanceTester);
    }
    
    final public function checkTotal(): void
    {
        $this->checkoutOverviewPage->checkTitlePage();
        
        $this->checkCartIsNotEmpty();
        
        $this->checkoutOverviewPage->checkCalculationTotals();
    }
    
    final public function clickButtonFinish(): void
    {
        $this->checkoutOverviewPage->checkTitlePage();
        
        $this->safeClick($this->checkoutOverviewPage->returnButtonFinish());
        
        $this->checkoutOverviewPage->waitForPageNotVisible();
    }
}
