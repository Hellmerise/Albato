<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Exception\LoginException;
use Tests\Support\Page\Abstract\AbstractPage;


/**
 * Страница авторизации.
 * Описывает элементы страницы и предоставляет методы для их получения.
 */
final class LoginPage extends AbstractPage
{
    private const string URL = '/';
    /**
     * @var string Xpath поля для ввода логина.
     */
    private const string FIELD_USERNAME_XPATH = "//input[@data-test = 'username']";
    
    /**
     * @var string Xpath поля для ввода пароля.
     */
    private const string FIELD_PASSWORD_XPATH = "//input[@data-test = 'password']";
    
    /**
     * @var string Xpath кнопки авторизации.
     */
    private const string BUTTON_LOGIN_XPATH = "//input[@data-test = 'login-button']";
    
    /**
     * @var string Xpath элемента ошибки.
     */
    private const string MESSAGE_ERROR_XPATH = "//h3[@data-test = 'error']";
    
    /**
     * @var string Ошибка при пустом поле "username", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_INVALID_USERNAME = 'Epic sadface: Username is required';
    
    /**
     * @var string Ошибка при пустом поле "password", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_INVALID_PASSWORD = 'Epic sadface: Password is required';
    
    /**
     * @var string Ошибка при некорректной паре логина и пароля.
     */
    private const string MESSAGE_ERROR_INCORRECT_DATA = 'Epic sadface: Username and password do not match any user in this service';
    
    /**
     * @var string Ошибка при авторизации под заблокированным пользователем.
     */
    private const string MESSAGE_ERROR_LOCKED_USER = 'Epic sadface: Sorry, this user has been locked out.';
    
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * Заполняет поле для ввода логина.
     *
     * @param string $value Логин пользователя.
     *
     * @return void
     */
    final public function fillUsername(string $value): void
    {
        $this->assertFieldEmpty(self::FIELD_USERNAME_XPATH);
        $this->fillField(self::FIELD_USERNAME_XPATH, $value);
    }
    
    /**
     * Заполняет поле для ввода пароля.
     *
     * @param string $value Пароль пользователя.
     *
     * @return void
     */
    final public function fillPassword(string $value): void
    {
        $this->assertFieldEmpty(self::FIELD_PASSWORD_XPATH);
        $this->fillField(self::FIELD_PASSWORD_XPATH, $value);
    }
    
    /**
     * Нажимает на кнопку входа в систему.
     *
     * @param bool $assertLogin Утверждение успешности входа.
     *
     * - Если `true`, то ожидается успешный вход в систему.
     * - Если `false`, то ожидается ошибка.
     *
     * @return void
     */
    final public function clickLogin(bool $assertLogin): void
    {
        self::$acceptanceTester->click(self::BUTTON_LOGIN_XPATH);
        
        if ($assertLogin && $this->isErrorLogin()) {
            self::$acceptanceTester->fail($this->getMessageErrorLogin());
        }
    }
    
    /**
     * Возвращает `true` если при авторизации отображается ошибка на форме входа.
     *
     * @return bool
     */
    final public function isErrorLogin(): bool
    {
        return self::$acceptanceTester->tryToSeeElement(self::MESSAGE_ERROR_XPATH);
    }
    
    /**
     * Получает текст сообщения об ошибке из страницы.
     *
     * @return string Текст сообщения об ошибке.
     */
    final public function getMessageErrorLogin(): string
    {
        return self::$acceptanceTester->grabTextFrom(self::MESSAGE_ERROR_XPATH);
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненном поле логина.
     *
     * @return string
     */
    final public function getMessageInvalidUsername(): string
    {
        return self::MESSAGE_ERROR_INVALID_USERNAME;
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненном поле пароля.
     *
     * @return string
     */
    final public function getMessageInvalidPassword(): string
    {
        return self::MESSAGE_ERROR_INVALID_PASSWORD;
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при некорректном логине или пароле.
     *
     * @return string
     */
    final public function getMessageIncorrectData(): string
    {
        return self::MESSAGE_ERROR_INCORRECT_DATA;
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при авторизации под заблокированным пользователем.
     *
     * @return string
     */
    final public function getMessageLockedUser(): string
    {
        return self::MESSAGE_ERROR_LOCKED_USER;
    }
    
    
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
    final protected array $wait_elements {
        get {
            return [
                self::FIELD_USERNAME_XPATH,
                self::FIELD_PASSWORD_XPATH,
                self::BUTTON_LOGIN_XPATH,
            ];
        }
    }
}
