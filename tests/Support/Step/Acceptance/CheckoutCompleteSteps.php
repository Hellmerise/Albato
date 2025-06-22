<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\SuccessPaymentEnum;
use Tests\Support\Page\Acceptance\CheckoutCompletePage;

class CheckoutCompleteSteps extends BaseSteps
{
    private readonly CheckoutCompletePage $checkoutCompletePage;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        $this->checkoutCompletePage = new CheckoutCompletePage($acceptanceTester);
    }
    
    final public function checkSuccessPayment(): void
    {
        $this->checkoutCompletePage->checkTitlePage();
        
        $this->assertEquals(SuccessPaymentEnum::HEADER->value, $this->checkoutCompletePage->returnTextHeader());
        $this->assertEquals(SuccessPaymentEnum::MESSAGE->value, $this->checkoutCompletePage->returnTextMessage());
        $this->assertEquals(0, $this->checkoutCompletePage->returnValueItemsInCart());
    }
    
    final public function clickButtonHome(): void
    {
        $this->checkoutCompletePage->checkTitlePage();
        
        $this->safeClick($this->checkoutCompletePage->returnButtonBackToHome());
        
        $this->checkoutCompletePage->waitForPageNotVisible();
    }
}
