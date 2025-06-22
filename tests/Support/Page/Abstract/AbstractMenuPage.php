<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use Tests\Support\Page\Interfaces\MenuInterface;

abstract class AbstractMenuPage extends AbstractPage implements MenuInterface
{
    private const string LOGO_TEXT              = 'Swag Labs';
    private const string LOGO_XPATH             = "//div[@class = 'app_logo']";
    private const string TITLE_XPATH            = "//span[@data-test = 'title']";
    private const string BUTTON_MENU            = "//button[@id = 'react-burger-menu-btn']";
    private const string BUTTON_CART            = "//a[@data-test = 'shopping-cart-link']";
    private const string CART_VALUE_XPATH       = "//span[@data-test = 'shopping-cart-badge']";
    private const string BUTTON_ALL_ITEMS       = "//a[@data-test = 'inventory-sidebar-link']";
    private const string BUTTON_ABOUT           = "//a[@data-test = 'about-sidebar-link']";
    private const string BUTTON_LOGOUT          = "//a[@data-test = 'logout-sidebar-link']";
    private const string BUTTON_RESET_APP_STATE = "//a[@data-test = 'reset-sidebar-link']";
    protected static string $title = '';
    
    final public function checkLogo(): void
    {
        $expected = self::LOGO_TEXT;
        $actual = static::$acceptanceTester->grabTextFrom(self::LOGO_XPATH);
        $this->assertTextFor('LOGO', $expected, $actual);
    }
    
    final public function checkTitlePage(): void
    {
        $expected = static::$title;
        $actual = static::$acceptanceTester->grabTextFrom(self::TITLE_XPATH);
        $this->assertTextFor('TITLE', $expected, $actual);
    }
    
    final public function returnButtonMenu(): string
    {
        return self::BUTTON_MENU;
    }
    
    final public function returnButtonCart(): string
    {
        return self::BUTTON_CART;
    }
    
    final public function returnButtonAllItems(): string
    {
        return self::BUTTON_ALL_ITEMS;
    }
    
    final public function returnButtonAbout(): string
    {
        return self::BUTTON_ABOUT;
    }
    
    final public function returnButtonLogout(): string
    {
        return self::BUTTON_LOGOUT;
    }
    
    final public function returnButtonResetAppState(): string
    {
        return self::BUTTON_RESET_APP_STATE;
    }
    
    final public function returnValueItemsInCart(): int
    {
        return static::$acceptanceTester->tryToSeeElement(self::CART_VALUE_XPATH)
            ? (int)static::$acceptanceTester->grabTextFrom(self::CART_VALUE_XPATH)
            : 0;
    }
    
    final protected static function returnElementsMenu(): array
    {
        return [
            self::LOGO_XPATH,
            self::BUTTON_CART,
            self::BUTTON_MENU
        ];
    }
    
    private function assertTextFor(string $label, string $expected, string $actual): void
    {
        static::$acceptanceTester->assertEquals(
            $expected,
            $actual,
            sprintf(
                "Ожидаемый текст для '%s' должен быть: '%s', но фактический текст: '%s'.",
                $label,
                $expected,
                $actual
            )
        );
    }
}