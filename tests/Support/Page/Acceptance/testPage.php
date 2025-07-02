<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractMenuPage;

/**
 * Страница завершения оформления заказа.
 *
 * Содержит методы для работы с элементами страницы успешного завершения оформления заказа,
 * включая получение текста сообщения, заголовка и навигацию обратно на главную страницу.
 */
final class testPage extends AbstractMenuPage
{
    /**
     * @var string URL страницы.
     */
    private const string URL = '/checkout-complete.html';
    
    /**
     * @var string Заголовок страницы.
     */
    private const string   TITLE = 'Checkout: Complete!';
    
    /** @var string Xpath для заголовка сообщения. */
    private const string HEADER = "//h2[@data-test = 'complete-header']";
    
    /** @var string Xpath для текста сообщения. */
    private const string MESSAGE = "//div[@data-test = 'complete-text']";
    
    /** @var string Xpath для кнопки возврата домой. */
    private const string BUTTON_BACK_TO_HOME = "//button[@data-test = 'back-to-products']";
    
    /**
     * @inheritDoc
     */
    final protected string      $url {
        get {
            return self::URL;
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected string      $title {
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
                self::HEADER,
                self::MESSAGE,
                self::BUTTON_BACK_TO_HOME,
            ];
        }
    }
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * Возвращает Xpath кнопки для возврата домой.
     *
     * @return string
     */
    final public function getButtonBackToHome(): string
    {
        return self::BUTTON_BACK_TO_HOME;
    }
}
