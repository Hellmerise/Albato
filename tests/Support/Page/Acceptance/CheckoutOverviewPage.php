<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use InvalidArgumentException;
use Tests\Support\Config\InventoryColumnEnum;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractMenuPage;
use Tests\Support\Page\Interfaces\CalculationInterface;

final class CheckoutOverviewPage extends CartPage implements CalculationInterface
{
    private const float    TAX_RATE            = 8.0;
    private const string   SUMMARY_INFO_XPATH  = "//div[@class = 'summary_info']";
    private const string   PAYMENT_INFO_LABEL  = "//div[@data-test = 'payment-info-label']";
    private const string   PAYMENT_INFO_VALUE  = "//div[@data-test = 'payment-info-value']";
    private const string   SHIPPING_INFO_LABEL = "//div[@data-test = 'shipping-info-label']";
    private const string   SHIPPING_INFO_VALUE = "//div[@data-test = 'shipping-info-value']";
    private const string   TOTAL_INFO_LABEL    = "//div[@data-test = 'total-info-label']";
    private const string   BUTTON_FINISH       = "//button[@id = 'finish']";
    private const string   SUBTOTAL_XPATH      = "//div[@data-test = 'subtotal-label']";
    private const string   TAX_XPATH           = "//div[@data-test = 'tax-label']";
    private const string   TOTAL_XPATH         = "//div[@data-test = 'total-label']";
    
    protected static string $title = "Checkout: Overview";
    
    
    final public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);
        self::$acceptanceTester = $I;
    }
    
    final public function returnButtonFinish(): string
    {
        return self::BUTTON_FINISH;
    }
    
    final public function checkCalculationTotals(bool $safeMode = false): void
    {
        $this->checkCalculation('subtotal', $safeMode);
        $this->checkCalculation('tax', $safeMode);
        $this->checkCalculation('total', $safeMode);
    }
    
    final public function assertCalculationTotalIsNull(bool $safeMode = false): void
    {
        $expectedValue = 0.00;
        $this->checkCalculation('subtotal', $safeMode, $expectedValue);
        $this->checkCalculation('tax', $safeMode, $expectedValue);
        $this->checkCalculation('total', $safeMode, $expectedValue);
    }
    
    final protected function returnElementsForWait(): array
    {
        return [
            parent::returnCartListXpath(),
            self::SUMMARY_INFO_XPATH,
            self::PAYMENT_INFO_LABEL,
            self::PAYMENT_INFO_VALUE,
            self::SHIPPING_INFO_LABEL,
            self::SHIPPING_INFO_VALUE,
            self::TOTAL_INFO_LABEL,
            self::SUBTOTAL_XPATH,
            self::TAX_XPATH,
            self::TOTAL_XPATH,
            self::BUTTON_FINISH,
        ];
    }
    
    private function checkCalculation(string $labelTotal, bool $safeMode, ?float $expectedValue = null): void
    {
        $expectedSubtotal = $this->returnSubtotal($safeMode);
        $expectedTax = $this->returnTax($safeMode);
        $expectedTotal = $this->returnTotal($safeMode);
        
        
        $calculateSubtotal = $this->returnCalculateTotal();
        $calculateTax = $this->returnCalculateTax($calculateSubtotal);
        $calculateTotal = round($calculateSubtotal + $calculateTax, 2);
        
        [$expected, $actual] = match ($labelTotal) {
            'subtotal' => [$expectedValue ?? $expectedSubtotal, $calculateSubtotal],
            'tax' => [$expectedValue ?? $expectedTax, $calculateTax],
            'total' => [$expectedValue ?? $expectedTotal, $calculateTotal],
            default => throw new InvalidArgumentException("Некорректный ключ: '$labelTotal'")
        };
        
        $this->assertCalculatedValue($labelTotal, $expected, $actual);
    }
    
    private function returnSubtotal(bool $safeMode): float
    {
        return self::$acceptanceTester->grabFloatFrom(self::SUBTOTAL_XPATH, $safeMode);
    }
    
    private function returnTax($safeMode): float
    {
        return self::$acceptanceTester->grabFloatFrom(self::TAX_XPATH, $safeMode);
    }
    
    private function returnTotal($safeMode): float
    {
        return self::$acceptanceTester->grabFloatFrom(self::TOTAL_XPATH, $safeMode);
    }
    
    private function returnCalculateTotal(): float
    {
        $items = $this->returnListProductsFromPage();
        
        return round(
            array_reduce(
                $items,
                function ($sum, $item) {
                    return $sum + ($item[InventoryColumnEnum::QUANTITY->value] * $item[InventoryColumnEnum::PRICE->value]);
                },
                0.0
            ),
            2
        );
    }
    
    private function returnCalculateTax(float $total): float
    {
        return round(($total * self::TAX_RATE) / 100, 2);
    }
    
    private function assertCalculatedValue(string $label, float $expected, float $actual): void
    {
        self::$acceptanceTester->assertEquals(
            $expected,
            $actual,
            sprintf(
                "Ожидалось, что '%s' будет равно '%s', но при расчетах получилось: '%s'",
                $label,
                $expected,
                $actual
            )
        );
    }
}
