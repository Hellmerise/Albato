<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\InventoryColumnEnum;
use Tests\Support\Page\Acceptance\CartPage;
use Tests\Support\Step\Interfaces\CartInterface;

class CartSteps extends InventorySteps implements CartInterface
{
    private readonly CartPage $cartPage;
    
    public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario, $acceptanceTester);
        $this->cartPage = new CartPage($acceptanceTester);
    }
    
    
    final public function clickButtonCheckout(): void
    {
        $this->cartPage->checkTitlePage();
        
        $this->safeClick($this->cartPage->returnButtonCheckout());
        
        $this->cartPage->waitForPageNotVisible();
    }
    
    final public function checkCartIsNotEmpty(): void
    {
        $valueInCart = $this->cartPage->returnValueItemsInCart();
        $itemsInCart = $this->cartPage->returnListProductsFromPage();
        
        $this->assertGreaterOrEquals(1, $valueInCart, "Ожидалось, что счетчик товаров в корзине будет показывать не меньше 1 товара");
        $this->assertGreaterOrEquals(1, count($itemsInCart), "Ожидалось, что количество товаров в списке будет не меньше 1");
    }
    
    final public function clearCart(): void
    {
        $items = $this->cartPage->returnListProductsFromPage();
        
        while (count($items) > 0) {
            $this->removeFromCart($items[0][InventoryColumnEnum::NAME->value]);
            $items = $this->cartPage->returnListProductsFromPage();
        }
    }
    
    final protected function checkEmptyCart(): void
    {
        $this->assertEquals(0, $this->cartPage->returnValueItemsInCart());
        $this->assertEmpty($this->cartPage->returnListProductsFromPage());
    }
}
