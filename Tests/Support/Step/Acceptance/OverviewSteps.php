<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\TestConfigEnum;
use Tests\Support\Page\Acceptance\OverviewPage;

final class OverviewSteps extends AcceptanceTester
{
    private const float TAX_RATE = TestConfigEnum::TAX_RATE;
    private readonly OverviewPage $overviewPage;
    private readonly array        $listProducts;
    private readonly int          $countProducts;
    private readonly string       $keyPrice;
    private readonly float        $subTotal;
    private readonly float        $tax;
    private readonly float        $total;
    
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        $this->overviewPage = new OverviewPage($acceptanceTester);
        
        $this->listProducts = $this->overviewPage->getProductsFromPage();
        $this->countProducts = $this->overviewPage->getCountProductsOnPage();
        
        $this->keyPrice = $this->overviewPage->getKeyPrice();
        
        $this->subTotal = $this->overviewPage->getSubTotal();
        $this->tax = $this->overviewPage->getTaxTotal();
        $this->total = $this->overviewPage->getTotal();
    }
    
    final public function checkCalculation(array $products): void
    {
        $this->comment('Рассчитываю данные для фактического списка продуктов');
        $this->checkCalculate($this->listProducts);
        
        $this->comment('Рассчитываю данные для переданного списка продуктов');
        $this->checkCalculate($products);
    }
    
    final public function clickButtonFinish(): void
    {
        $this->overviewPage->assertHeaderPage();
        
        $this->overviewPage->clickButtonFinish();
        
        $this->overviewPage->waitForPageNotVisible();
    }
    
    private function checkCalculate(array $listProducts): void
    {
        $subTotal = $this->calculateSubTotal($listProducts);
        $tax = $this->calculateTaxTotal($listProducts);
        $total = $this->calculateTotal($listProducts);
        
        $this->assertTotalEquals($subTotal, $tax, $total);
    }
    
    private function calculateSubTotal(array $listProducts): float
    {
        $result = 0.0;
        
        foreach ($listProducts as $product) {
            $result += $product[$this->keyPrice];
        }
        
        return $result;
    }
    
    private function calculateTaxTotal(array $listProducts): float
    {
        $total = $this->calculateSubTotal($listProducts);
        
        return round($total / 100 * self::TAX_RATE, 2);
    }
    
    private function calculateTotal(array $listProducts): float
    {
        $total = $this->calculateSubTotal($listProducts);
        $tax = $this->calculateTaxTotal($listProducts);
        
        return $total + $tax;
    }
    
    private function assertTotalEquals(float $subTotal, float $tax, float $total): void
    {
        $this->assertEquals($this->subTotal, $subTotal, 'Итоговая сумма товаров отличается от рассчитанной');
        $this->assertEquals($this->tax, $tax, 'Итоговый налог отличается от рассчитанного');
        $this->assertEquals($this->total, $total, 'Итоговая сумма к оплате отличается от рассчитанной');
    }
}
