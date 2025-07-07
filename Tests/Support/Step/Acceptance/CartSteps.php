<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\CartPage;

final class CartSteps extends AcceptanceTester
{
    private readonly CartPage $cartPage;
    private readonly string   $keyId;
    private readonly string   $keyName;
    private readonly string   $keyDescription;
    private readonly string   $keyPrice;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        
        $this->cartPage = new CartPage($acceptanceTester);
        
        $this->keyId = $this->cartPage->getKeyId();
        $this->keyName = $this->cartPage->getKeyName();
        $this->keyDescription = $this->cartPage->getKeyDescription();
        $this->keyPrice = $this->cartPage->getKeyPrice();
    }
    
    /**
     * Нажимает по кнопке "Оформить заказ" на странице корзины.
     *
     * Перед этим проверяет, что заголовок страницы корректен.
     *
     * Ожидает исчезновение текущей страницы.
     *
     * @return void
     */
    final public function clickButtonCheckout(): void
    {
        $this->cartPage->assertHeaderPage();
        
        $this->cartPage->clickButtonCheckout();
        
        $this->cartPage->waitForPageNotVisible();
    }
    
    /**
     * Проверяет, что список продуктов в корзине соответствует ожидаемому.
     *
     * Если ожидаемый список пуст, очищает корзину.
     * Выполняет проверку количества продуктов и их содержимого.
     *
     * @param array $expectedProducts Ожидаемый список продуктов
     *
     * @return void
     */
    final public function assertProductsEqual(array $expectedProducts): void
    {
        $this->cartPage->assertHeaderPage();
        
        if (empty($expectedProducts)) {
            $this->clearCart();
        }
        
        $this->checkingCountProducts($expectedProducts);
        $this->assertProductsEquals($expectedProducts);
    }
    
    /**
     * Проверяет, что количество продуктов на странице и в счетчике корзины
     * совпадает с количеством ожидаемых продуктов.
     *
     * @param array $products Список товаров
     *
     * @return void
     */
    private function checkingCountProducts(array $products): void
    {
        $expectedCount = $this->cartPage->getCountProductsOnPage();
        $countItemsInCart = $this->cartPage->getValueCart();
        
        $this->assertCount($expectedCount, $products, 'Количество товаров в корзине не совпадает с количеством добавленных товаров');
        $this->assertEquals($countItemsInCart, $expectedCount, 'Количество товаров в счетчике не совпадает с количеством товаров в корзине');
    }
    
    /**
     * Сравнивает ожидаемый и фактический списки продуктов по выбранным полям.
     *
     * @param array $expectedProducts Ожидаемый список товаров
     *
     * @return void
     */
    private function assertProductsEquals(array $expectedProducts): void
    {
        $actualProducts = $this->cartPage->getProductsFromPage();
        
        $expectedFiltered = $this->filterProductFields($expectedProducts);
        $actualFiltered = $this->filterProductFields($actualProducts);
        
        $this->assertEquals($expectedFiltered, $actualFiltered, 'Добавленный список товаров отличается от списка товаров в корзине');
    }
    
    /**
     * Отбирает из каждого продукта только нужные поля для сравнения.
     *
     * @param array $products Список товаров
     *
     * @return array Отфильтрованный список продуктов с ключами id, name, description, price
     *
     * @return void
     */
    private function filterProductFields(array $products): array
    {
        $keys = [
            $this->keyId,
            $this->keyName,
            $this->keyDescription,
            $this->keyPrice,
        ];
        
        return array_map(
            function ($product) use ($keys) {
                $result = [];
                foreach ($keys as $key) {
                    $result[$key] = $product[$key] ?? null;
                }
                
                return $result;
            },
            $products
        );
    }
    
    /**
     * Очищает корзину, удаляя все продукты по одному.
     *
     * Нажимает по кнопке удаления для каждого продукта до тех пор,
     * пока корзина не станет пустой.
     */
    private function clearCart(): void
    {
        $products = $this->cartPage->getProductsFromPage();
        $keyButton = $this->cartPage->getKeyButton();
        
        while (count($products) > 0) {
            $this->click($products[0][$keyButton]);
            $products = $this->cartPage->getProductsFromPage();
        }
    }
}
