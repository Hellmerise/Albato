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
    
    final public function checkCartIsEmpty(): void
    {
        $this->checkoutOverviewPage->checkTitlePage();
        
        $valueInCart = $this->checkoutOverviewPage->returnValueItemsInCart();
        $itemsInCart = $this->checkoutOverviewPage->returnListProductsFromPage();
        
        $this->assertEquals(0, $valueInCart, "Ожидалось, что счетчик товаров в корзине будет пустой");
        $this->assertEmpty($itemsInCart,"Ожидалось, что товаров в списке не будет");
        
        $this->checkoutOverviewPage->assertCalculationTotalIsNull();
    }
    
    final public function clickButtonFinish(): void
    {
        $this->checkoutOverviewPage->checkTitlePage();
        
        $this->safeClick($this->checkoutOverviewPage->returnButtonFinish());
        
        $this->checkoutOverviewPage->waitForPageNotVisible();
    }
}
