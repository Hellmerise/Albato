<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractProductPage;

/**
 * Страница корзины.
 *
 * Предоставляет элементы страницы корзины,
 * включая кнопки оформления заказа и продолжения покупок,
 * а также список товаров в корзине.
 */
class CartPage extends AbstractProductPage
{
    private const string   URL                            = '/cart.html';
    private const string   TITLE                          = 'Your Cart';
    private const string   BUTTON_CHECKOUT_XPATH          = "//button[@data-test = 'checkout']";
    private const string   BUTTON_CONTINUE_SHOPPING_XPATH = "//button[@data-test = 'continue-shopping']";
    
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
                self::BUTTON_CHECKOUT_XPATH,
                self::BUTTON_CONTINUE_SHOPPING_XPATH,
            ];
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $container_data_test {
        get {
            return parent::getContainerCartList();
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $container_pattern_xpath {
        get {
            return sprintf(parent::getPatternContainerXpath(), $this->container_data_test);
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $key_name {
        get {
            return parent::getKeyName();
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $key_price {
        get {
            return parent::getKeyPrice();
        }
    }
    
    public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    final public function clickButtonCheckout(): void
    {
        self::$acceptanceTester->click(self::BUTTON_CHECKOUT_XPATH);
    }
    
    final public function clickButtonShopping(): void
    {
        self::$acceptanceTester->click(self::BUTTON_CONTINUE_SHOPPING_XPATH);
    }
}
