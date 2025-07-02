<?php

declare(strict_types=1);


namespace Tests\Acceptance;

use Codeception\Attribute\Group;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Step\Acceptance\CartSteps;
use Tests\Support\Step\Acceptance\CompleteSteps;
use Tests\Support\Step\Acceptance\InformationSteps;
use Tests\Support\Step\Acceptance\OverviewSteps;
use Tests\Support\Step\Acceptance\InventorySteps;

#[Group('fifth')]
final class CheckingProcessingAddingAndPaymentCest
{
    private InventorySteps $inventorySteps;
    private CartSteps             $cartSteps;
    private InformationSteps $checkoutInformationSteps;
    private OverviewSteps    $checkoutOverviewSteps;
    private CompleteSteps    $checkoutCompleteSteps;
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->inventorySteps = new InventorySteps($scenario, $I);
        $this->cartSteps = new CartSteps($scenario, $I);
        $this->checkoutInformationSteps = new InformationSteps($scenario, $I);
        $this->checkoutOverviewSteps = new OverviewSteps($scenario, $I);
        $this->checkoutCompleteSteps = new CompleteSteps($scenario, $I);
    }

    private function testProcessingAddingAndPayment(AcceptanceTester $I): void
    {
        
        $this->inventorySteps->loginAsStandardUser();
        $this->testAddingInCart($I);
        $this->checkCart($I);
        $this->fillInformation($I);
        $this->checkPayment($I);
        $this->checkSuccessPayment($I);
        $this->checkoutCompleteSteps->clickButtonHome();
        
    }
    
    public function testProcessingPaymentWithEmptyCart(AcceptanceTester $I): void
    {
        $I->wantTo("Оформление заказа с пустой корзиной");
        $this->inventorySteps->loginAsStandardUser();
        $this->inventorySteps->clickButtonCart();
        $this->cartSteps->clearCart();
        //$this->cartSteps->clickButtonCheckout();
        $this->fillInformation($I);
        $this->checkoutOverviewSteps->checkCartIsEmpty();
        $this->checkoutOverviewSteps->clickButtonFinish();
        $this->checkSuccessPayment($I);
    }
    
    private function testAddingInCart(AcceptanceTester $I): void
    {
        $I->comment("Проверить добавление товаров в корзину...");
        $this->inventorySteps->addProductsInCart();
        $this->inventorySteps->clickButtonCart();
    
    }
    
    private function checkCart(AcceptanceTester $I): void
    {
        $I->comment("Проверить корзину...");
        $this->cartSteps->assertNotEmptyCart();
        //$this->cartSteps->clickButtonCheckout();
    }
    
    private function fillInformation(AcceptanceTester $I): void
    {
        $I->comment("Заполнить информацию о себе...");
        
        $data = [
            CheckoutInformationEnum::FIRSTNAME->value   => "Дмитрий",
            CheckoutInformationEnum::LASTNAME->value    => "Базарнов",
            CheckoutInformationEnum::POSTAL_CODE->value => "1234567",
        ];
        
        $this->checkoutInformationSteps->fillFields($data);
        $this->checkoutInformationSteps->clickButtonContinue();
    }
    
    private function checkPayment(AcceptanceTester $I): void
    {
        $I->comment("Проверить итоговую сумму...");
        $this->checkoutOverviewSteps->checkTotal();
        $this->checkoutOverviewSteps->clickButtonFinish();
    }
    
    private function checkSuccessPayment(AcceptanceTester $I): void
    {
        $I->comment("Проверить успешность оплаты");
        $this->checkoutCompleteSteps->checkSuccessPayment();
    }
}
