<?php

declare(strict_types=1);


namespace Tests\Acceptance;

use Codeception\Attribute\Group;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\CheckoutInformationEnum;
use Tests\Support\Step\Acceptance\CartSteps;
use Tests\Support\Step\Acceptance\CheckoutCompleteSteps;
use Tests\Support\Step\Acceptance\CheckoutInformationSteps;
use Tests\Support\Step\Acceptance\CheckoutOverviewSteps;
use Tests\Support\Step\Acceptance\InventorySteps;

#[Group('fourth')]
final class CheckingProcessingAddingAndPaymentCest
{
    private InventorySteps $inventorySteps;
    private CartSteps $cartSteps;
    private CheckoutInformationSteps $checkoutInformationSteps;
    private CheckoutOverviewSteps $checkoutOverviewSteps;
    private CheckoutCompleteSteps $checkoutCompleteSteps;
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->inventorySteps = new InventorySteps($scenario, $I);
        $this->cartSteps = new CartSteps($scenario, $I);
        $this->checkoutInformationSteps = new CheckoutInformationSteps($scenario, $I);
        $this->checkoutOverviewSteps = new CheckoutOverviewSteps($scenario, $I);
        $this->checkoutCompleteSteps = new CheckoutCompleteSteps($scenario, $I);
    }

    public function testProcessingAddingAndPayment(AcceptanceTester $I): void
    {
        $I->wantTo("Полное оформление заказа с добавлением товара в корзину и оплатой");
        $this->inventorySteps->loginAsStandardUser();
        $this->testAddingInCart($I);
        $this->checkCart($I);
        $this->fillInformation($I);
        $this->checkPayment($I);
        $this->checkSuccessPayment($I);
        $this->checkoutCompleteSteps->clickButtonHome();
        
    }
    
    private function testAddingInCart(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить добавление товаров в корзину...");
        $this->inventorySteps->fillCart();
        $this->inventorySteps->clickButtonCart();
    
    }
    
    private function checkCart(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить корзину...");
        $this->cartSteps->checkCartIsNotEmpty();
        $this->cartSteps->clickButtonCheckout();
    }
    
    private function fillInformation(AcceptanceTester $I): void
    {
        $I->wantTo("Заполнить информацию о себе...");
        
        $data = [
            CheckoutInformationEnum::FIRSTNAME->value   => "Дмитрий",
            CheckoutInformationEnum::LASTNAME->value    => "Базарнов",
            CheckoutInformationEnum::POSTAL_CODE->value => "1234567",
        ];
        
        $this->checkoutInformationSteps->fillInformation($data);
        $this->checkoutInformationSteps->clickButtonContinue();
    }
    
    private function checkPayment(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить итоговую сумму...");
        $this->checkoutOverviewSteps->checkTotal();
        $this->checkoutOverviewSteps->clickButtonFinish();
    }
    
    private function checkSuccessPayment(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить успешность оплаты");
        $this->checkoutCompleteSteps->checkSuccessPayment();
    }
}
