<?php

declare(strict_types=1);


namespace Tests\Acceptance\ScenarioTest;


use Tests\Acceptance\ScenarioTest\Traits\LoginStepsTrait;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\TestCasesEnum;
use Tests\Support\Page\Acceptance\InventoryPage;
use Tests\Support\Step\Acceptance\CartSteps;
use Tests\Support\Step\Acceptance\CompleteSteps;
use Tests\Support\Step\Acceptance\InformationSteps;
use Tests\Support\Step\Acceptance\InventorySteps;
use Tests\Support\Step\Acceptance\OverviewSteps;

abstract class CasePurchaseProducts
{
    use LoginStepsTrait;
    
    protected const string KEY_COUNT       = TestCasesEnum::KEY_COUNTS_PRODUCTS;
    protected const string KEY_FIRSTNAME   = TestCasesEnum::KEY_FIRSTNAME;
    protected const string KEY_LASTNAME    = TestCasesEnum::KEY_LASTNAME;
    protected const string KEY_POSTAL_CODE = TestCasesEnum::KEY_POSTAL_CODE;
    
    final protected function testPurchaseProducts(?int $countProducts, ?string $firstname, ?string $lastname, ?string $postalCode): void
    {
        $selectedProducts = $this->selectProducts($countProducts);
        
        $this->checkingProductsInCart($selectedProducts);
        $this->fillPurchaserInformation($firstname, $lastname, $postalCode);
        $this->checkingOverview($selectedProducts);
        $this->checkingSuccessPayment();
    }
    
    private function selectProducts(?int $countProducts): array
    {
        $inventorySteps = new InventorySteps($this->scenario, $this->acceptanceTester);
        
        $result = $inventorySteps->addProductsInCart($countProducts);
        
        $inventorySteps->clickButtonCart();
        
        return $result;
    }
    
    private function checkingProductsInCart(array $products): void
    {
        $cartSteps = new CartSteps($this->scenario, $this->acceptanceTester);
        
        $cartSteps->assertProductsEqual($products);
        
        $cartSteps->clickButtonCheckout();
    }
    
    private function fillPurchaserInformation(?string $firstname, ?string $lastname, ?string $postalCode): void
    {
        $informationSteps = new InformationSteps($this->scenario, $this->acceptanceTester);
        
        $informationSteps->writeInformationAndContinue($firstname, $lastname, $postalCode);
    }
    
    private function checkingOverview(array $products): void
    {
        $overviewSteps = new OverviewSteps($this->scenario, $this->acceptanceTester);
        
        $overviewSteps->checkCalculation($products);
        
        $overviewSteps->clickButtonFinish();
    }
    
    private function checkingSuccessPayment(): void
    {
        $completeSteps = new CompleteSteps($this->scenario, $this->acceptanceTester);
        
        $completeSteps->seeSuccessPayment();
        $completeSteps->assertCartEmpty();
    }
    
    /**
     * @dataProvider dataProvider
     */
    final protected function dataProvider(): array
    {
        return require dirname(__DIR__, 2) . '/Support/Data/Acceptance/testcases_for_purchase_products.php';
    }
}