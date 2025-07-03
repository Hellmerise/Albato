<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use Codeception\Exception\ElementNotFound;

/**
 * Абстрактный класс страницы с меню.
 *
 * Предоставляет базовую функциональность для работы с элементами меню:
 * - получение текста логотипа и заголовка
 * - доступ к кнопкам навигации
 * - работа с корзиной
 */
abstract class AbstractMenuPage extends AbstractPage
{
    /**
     * @var string Xpath заголовка страницы.
     */
    private const string PAGE_TITLE_XPATH = "//span[@data-test = 'title']";
    
    /**
     * @var string Xpath кнопки "Меню".
     */
    private const string BUTTON_MENU_XPATH = "//button[@id = 'react-burger-menu-btn']";
    
    /**
     * @var string Xpath кнопки "X" для закрытия меню.
     */
    private const string BUTTON_CLOSE_MENU_XPATH = "//button[@id = 'react-burger-cross-btn']";
    
    /**
     * @var string Xpath кнопки "Корзина".
     */
    private const string BUTTON_CART_XPATH = "//a[@data-test = 'shopping-cart-link']";
    
    /**
     * @var string Xpath счётчика "Корзины".
     */
    private const string CART_VALUE_XPATH = "//span[@data-test = 'shopping-cart-badge']";
    
    /**
     * @var string Xpath кнопки меню "Все товары".
     */
    private const string BUTTON_ALL_ITEMS_XPATH = "//a[@data-test = 'inventory-sidebar-link']";
    
    /**
     * @var string Xpath кнопки меню "О компании".
     */
    private const string BUTTON_ABOUT_XPATH = "//a[@data-test = 'about-sidebar-link']";
    
    /**
     * @var string Xpath кнопки меню "Выход".
     */
    private const string BUTTON_LOGOUT_XPATH = "//a[@data-test = 'logout-sidebar-link']";
    
    /**
     * @var string Xpath кнопки меню "Сбросить приложение".
     */
    private const string BUTTON_RESET_APP_XPATH = "//a[@data-test = 'reset-sidebar-link']";
    
    /**
     * @var string Текст заголовка страницы.
     */
    abstract protected string $title {
        get;
    }
    
    /**
     * Утверждает, что фактический текст логотипа соответствует ожидаемому.
     * Утверждает, что фактический текст заголовка страницы соответствует ожидаемому.
     *
     */
    final public function assertHeaderPage(): void
    {
        $this->assertLogoPage();
        $this->assertTitlePage();
    }
    
    /**
     * Нажимает на кнопку главного меню "Сбросить приложение".
     *
     * @return void
     */
    final public function clickResetApp(): void
    {
        $this->clickButtonMenu(self::BUTTON_RESET_APP_XPATH);
    }
    
    /**
     * Нажимает на кнопку главного меню "Выйти".
     *
     * @return void
     */
    final public function clickLogout(): void
    {
        $this->clickButtonMenu(self::BUTTON_LOGOUT_XPATH);
    }
    
    /**
     * Нажимает на кнопку "Корзина" в правом верхнем углу.
     *
     * @return void
     */
    final public function clickButtonCart(): void
    {
        static::$acceptanceTester->click(self::BUTTON_CART_XPATH);
    }
    
    /**
     * Получает количество товаров в корзине из счетчика корзины, который в правом верхнем углу.
     *
     * @return int
     */
    final public function getValueCart(): int
    {
        try {
            return (int)static::$acceptanceTester->grabTextFrom(self::CART_VALUE_XPATH);
        } catch (ElementNotFound) {
            return 0;
        }
    }
    
    /**
     * Нажимает по указанной кнопке меню.
     * Если кнопка меню не видна, сначала открывает меню.
     *
     * @param string $link Xpath кнопки меню.
     *
     * @return void
     */
    private function clickButtonMenu(string $link): void
    {
        if ($link !== self::BUTTON_MENU_XPATH && !static::$acceptanceTester->tryToSeeElement($link)) {
            static::$acceptanceTester->click(self::BUTTON_MENU_XPATH);
            static::$acceptanceTester->waitForElementVisible($link);
        }
        static::$acceptanceTester->click($link);
    }
    
    /**
     * Утверждает, что фактический текст заголовка страницы соответствует ожидаемому.
     *
     * @return void
     */
    private function assertTitlePage(): void
    {
        $title = static::$acceptanceTester->grabTextFrom(self::PAGE_TITLE_XPATH);
        static::$acceptanceTester->assertEquals(
            $this->title,
            $title,
            "Текст заголовка '$title' не соответствует ожидаемому!"
        );
    }
}
