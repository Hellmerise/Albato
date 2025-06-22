<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\Config\InventoryColumnEnum;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractListPage;

class CartPage extends AbstractListPage
{
    private const string           DATA_TEST_NAME_LIST      = "cart-list";
    private const string           CART_LIST                = "//div[@data-test = '" . self::DATA_TEST_NAME_LIST . "']";
    private const string           BUTTON_CHECKOUT          = "//button[@data-test = 'checkout']";
    private const string           BUTTON_CONTINUE_SHOPPING = "//button[@data-test = 'continue-shopping']";
    
    protected static string $title = "Your Cart";
    
    public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    final public function returnButtonCheckout(): string
    {
        return self::BUTTON_CHECKOUT;
    }
    
    protected string $data_test_name_list {
        get {
            return self::DATA_TEST_NAME_LIST;
        }
    }
    
    final protected static function returnCartListXpath(): string
    {
        return self::CART_LIST;
    }
    
    final protected array $item_selectors {
        get {
            return [
                InventoryColumnEnum::QUANTITY->value    => InventoryColumnEnum::QUANTITY->getXPath(),
                InventoryColumnEnum::NAME->value        => InventoryColumnEnum::NAME->getXPath(),
                InventoryColumnEnum::NAME_LINK->value   => InventoryColumnEnum::NAME_LINK->getXPath(),
                InventoryColumnEnum::DESCRIPTION->value => InventoryColumnEnum::DESCRIPTION->getXPath(),
                InventoryColumnEnum::PRICE->value       => InventoryColumnEnum::PRICE->getXPath(),
            ];
        }
    }
    
    protected function returnElementsForWait(): array
    {
        return [
            self::CART_LIST,
            self::BUTTON_CHECKOUT,
            self::BUTTON_CONTINUE_SHOPPING,
        ];
    }
}
