<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractMenuPage;

class CompletePage extends AbstractMenuPage
{
    /**
     * @var string URL страницы.
     */
    private const string URL = '/checkout-complete.html';
    
    /**
     * @var string Заголовок страницы.
     */
    private const string TITLE = 'Checkout: Complete!';
    
    /**
     * @var string Xpath для заголовка сообщения.
     */
    private const string HEADER_XPATH = "//h2[@data-test = 'complete-header']";
    
    /**
     * @var string Xpath для текста сообщения.
     */
    private const string MESSAGE_XPATH = "//div[@data-test = 'complete-text']";
    
    /**
     * @var string Xpath для кнопки возврата домой.
     */
    private const string BUTTON_BACK_TO_HOME_XPATH = "//button[@data-test = 'back-to-products']";
    public const string  SUCCESS_HEADER_TEXT       = "Thank you for your order!";
    public const string  SUCCESS_MESSAGE_TEXT      = "Your order has been dispatched, and will arrive just as fast as the pony can get there!";
    
    
    /**
     * @inheritDoc
     */
    final protected string $url {
        get {
            return self::URL;
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected string $title {
        get {
            return self::TITLE;
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected array $wait_elements {
        get {
            return [
                self::HEADER_XPATH,
                self::MESSAGE_XPATH,
                self::BUTTON_BACK_TO_HOME_XPATH,
            ];
        }
    }
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * Нажимает на кнопку возврата домой.
     *
     * @return void
     */
    final public function clickButtonHome(): void
    {
        self::$acceptanceTester->click(self::BUTTON_BACK_TO_HOME_XPATH);
    }
    
    final public function getHeaderSuccess(): string
    {
        return self::SUCCESS_HEADER_TEXT;
    }
    
    final public function getTextFromHeader(): string
    {
        return self::$acceptanceTester->grabTextFrom(self::HEADER_XPATH);
    }
    
    final public function getMessageSuccess(): string
    {
        return self::SUCCESS_MESSAGE_TEXT;
    }
    
    final public function getTextFromMessage(): string
    {
        return self::$acceptanceTester->grabTextFrom(self::MESSAGE_XPATH);
    }
}

