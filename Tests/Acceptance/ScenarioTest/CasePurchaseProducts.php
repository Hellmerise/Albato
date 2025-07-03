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
        
        
    }
    
    
    
    /*    final protected function testProcessPayment(Scenario $scenario, AcceptanceTester $I, bool $addProducts = true): void
        {
            $products = $this->addProductsInCart($scenario, $I, $addProducts);
            $this->checkCartPage($scenario, $I, $products);
            $this->fillInformation($scenario, $I);
            $this->checkCalculateTotal($scenario, $I, $products);
            
            $this->checkCompletion($scenario, $I);
        }
        
        private function addProductsInCart(Scenario $scenario, AcceptanceTester $I, bool $addProducts): array|null
        {
            $inventorySteps = new InventorySteps($scenario, $I);
            
            if ($addProducts) {
                $add_items = $inventorySteps->addProductsInCart();
            }
            
            $inventorySteps->clickButtonCart();
            
            return $addProducts
                ? $add_items
                : null;
        }
        
        private function checkCartPage(Scenario $scenario, AcceptanceTester $I, ?array $products): void
        {
            $cartSteps = new CartSteps($scenario, $I);
            
            if (!is_null($products)) {
                $cartSteps->assertProductsEqual($products);
            }
            
            $cartSteps->clickButtonCheckout();
        }
        
        private function fillInformation(Scenario $scenario, AcceptanceTester $I): void
        {
            $client = [
                TestCasesEnum::KEY_FIRSTNAME   => 'Дмитрий',
                TestCasesEnum::KEY_LASTNAME    => 'Базарнов',
                TestCasesEnum::KEY_POSTAL_CODE => '123456',
            ];
            
            $informationSteps = new InformationSteps($scenario, $I);
            
            $informationSteps->processFillingInformationFields($client);
        }
        
        private function checkCalculateTotal(Scenario $scenario, AcceptanceTester $I, ?array $products): void
        {
            $overviewSteps = new OverviewSteps($scenario, $I);
            
            $countInCart = $overviewSteps->getCountProductsInCart();
            
            if (!is_null($products)) {
                $overviewSteps->checkCalculation($products);
                $I->assertGreaterThan(0, $countInCart);
            } else {
                $overviewSteps->assertZeroTotal();
                $I->assertEquals(0, $countInCart);
            }
            
            $overviewSteps->clickButtonFinish();
        }
        
        private function checkCompletion(Scenario $scenario, AcceptanceTester $I): void
        {
            $completeSteps = new CompleteSteps($scenario, $I);
            $completeSteps->seeSuccessPayment();
            
            $completeSteps->assertCartEmpty();
        }*/
    
    /**
     * @dataProvider dataProvider
     */
    final protected function dataProvider(): array
    {
        return require dirname(__DIR__, 2) . '/Support/Data/Acceptance/testcases_for_purchase_products.php';
    }
}