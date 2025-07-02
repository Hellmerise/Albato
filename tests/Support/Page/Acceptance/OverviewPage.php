<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Codeception\Exception\TestRuntimeException;
use Tests\Support\AcceptanceTester;

/**
 * Страница подтверждения заказа.
 *
 * Отображает информацию о заказе, стоимости товаров, налогах и доставке.
 */
final class OverviewPage extends CartPage
{
    private const string URL   = '/checkout-step-two.html';
    private const string TITLE = 'Checkout: Overview';
    
    /**
     * @var string Xpath для кнопки "Завершение".
     */
    private const string   BUTTON_FINISH_XPATH = "//button[@id = 'finish']";
    
    /**
     * @var string Xpath для кнопки "Отмена".
     */
    private const string   BUTTON_CANCEL_XPATH = "//button[@id = 'cancel']";
    
    /**
     * @var string Xpath информационного блока по заказу.
     */
    private const string   SUMMARY_INFO_XPATH = "//div[@class = 'summary_info']";
    
    /**
     * @var string Xpath для Label заказа.
     */
    private const string   PAYMENT_INFO_LABEL = "//div[@data-test = 'payment-info-label']";
    
    /**
     * @var string Xpath для извлечения информации о заказе.
     */
    private const string   PAYMENT_INFO_VALUE = "//div[@data-test = 'payment-info-value']";
    
    /**
     * @var string Xpath для Label доставки.
     */
    private const string   SHIPPING_INFO_LABEL = "//div[@data-test = 'shipping-info-label']";
    
    /**
     * @var string Xpath для извлечения информации о доставке.
     */
    private const string   SHIPPING_INFO_VALUE = "//div[@data-test = 'shipping-info-value']";
    
    /**
     * @var string Xpath для Label стоимости.
     */
    private const string   TOTAL_INFO_LABEL = "//div[@data-test = 'total-info-label']";
    
    /**
     * @var string Xpath для извлечения стоимости товаров в корзине.
     */
    private const string   SUBTOTAL_XPATH = "//div[@data-test = 'subtotal-label']";
    
    /**
     * @var string Xpath для извлечения стоимости налога.
     */
    private const string   TAX_XPATH = "//div[@data-test = 'tax-label']";
    
    /**
     * @var string Xpath для извлечения общей стоимости заказа.
     */
    private const string   TOTAL_XPATH = "//div[@data-test = 'total-label']";
    
    /**
     * @inheritDoc
     */
    protected string $url {
        get {
            return self::URL;
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $title {
        get {
            return self::TITLE;
        }
    }
    
    /**
     * @inheritDoc
     */
    protected array $wait_elements {
        get {
            return [
                $this->container_pattern_xpath,
                self::SUMMARY_INFO_XPATH,
                self::PAYMENT_INFO_LABEL,
                self::SHIPPING_INFO_LABEL,
                self::TOTAL_INFO_LABEL,
                self::BUTTON_FINISH_XPATH,
                self::BUTTON_CANCEL_XPATH,
            ];
        }
    }
    
    final public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);
        self::$acceptanceTester = $I;
    }
    
    /**
     * @inheritDoc
     */
    final public function getProductsFromPage(): array
    {
        $items = parent::getProductsFromPage();
        
        $keyToRemove = parent::getKeyButton();
        
        foreach ($items as &$item) {
            if (array_key_exists($keyToRemove, $item)) {
                unset($item[$keyToRemove]);
            }
        }
        unset($item);
        
        return $items;
    }
    
    final public function clickButtonFinish(): void
    {
        self::$acceptanceTester->click(self::BUTTON_FINISH_XPATH);
    }
    
    final public function clickButtonCancel(): void
    {
        self::$acceptanceTester->click(self::BUTTON_CANCEL_XPATH);
    }
    
    final public function getSubTotal(): float
    {
        return $this->extractFloatFrom(self::SUBTOTAL_XPATH);
    }
    
    final public function getTaxTotal(): float
    {
        return $this->extractFloatFrom(self::TAX_XPATH);
    }
    
    final public function getTotal(): float
    {
        return $this->extractFloatFrom(self::TOTAL_XPATH);
    }
    
    /**
     * Возвращает номер заказа.
     *
     * @return int
     */
    final public function getNumberOrder(): int
    {
        $orderNumber = self::$acceptanceTester->grabTextFrom(self::PAYMENT_INFO_VALUE);
        
        if (!preg_match('/.*#(\d+)/', $orderNumber, $matches)) {
            throw new TestRuntimeException(sprintf("Не удалось извлечь номер заказа из строки: '%s'", $orderNumber));
        }
        
        return (int)$matches[1];
    }
    
    /**
     * Возвращает информацию о доставке.
     *
     * @return string
     */
    final public function getShippingInformation(): string
    {
        return self::$acceptanceTester->grabTextFrom(self::SHIPPING_INFO_VALUE);
    }
    
    
    private function extractFloatFrom(string $cssOrXPathOrRegex): float
    {
        $text = self::$acceptanceTester->grabTextFrom($cssOrXPathOrRegex);
        
        return self::$acceptanceTester->extractFloatFrom($text);
    }
}
