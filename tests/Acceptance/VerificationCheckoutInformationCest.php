<?php

declare(strict_types=1);


namespace Tests\Acceptance;


use Codeception\Attribute\Group;
use Codeception\Example;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\CartPage;
use Tests\Support\Step\Acceptance\CartSteps;
use Tests\Support\Step\Acceptance\CheckoutInformationSteps;
use Tests\Support\Step\Acceptance\InventorySteps;

#[Group('third')]
final class VerificationCheckoutInformationCest
{
    private InventorySteps $inventorySteps;
    private CartSteps $cartSteps;
    private CheckoutInformationSteps $checkoutInformationSteps;
    
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->inventorySteps = new InventorySteps($scenario, $I);
        $this->cartSteps = new CartSteps($scenario, $I);
        $this->checkoutInformationSteps = new CheckoutInformationSteps($scenario, $I);
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function testVerificationFields(AcceptanceTester $I, Example $example): void
    {
        $I->wantTo($example['wantTo']);
        $this->inventorySteps->loginAsStandardUser();
        $this->inventorySteps->clickButtonCart();
        $this->cartSteps->clickButtonCheckout();
        $this->checkoutInformationSteps->fillInformation($example['fields'], true);
    }
    
    private function dataProvider(): array
    {
        return require dirname(__DIR__) . '/Support/Data/Acceptance/testcases_for_checkout_information.php';
    }
}
