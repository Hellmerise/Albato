<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;
use Tests\Support\Page\Abstract\AbstractPage;


/**
 * Страница авторизации.
 * Описывает элементы страницы и предоставляет методы для их получения.
 */
final class LoginPage extends AbstractPage
{
    /**
     * @var string URL страницы.
     */
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
     * @var string Ошибка при пустом поле "username", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_REQUIRED_USERNAME = 'Epic sadface: Username is required';
    
    /**
     * @var string Ошибка при пустом поле "password", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_REQUIRED_PASSWORD = 'Epic sadface: Password is required';
    
    /**
     * @var string Ошибка при некорректной паре логина и пароля.
     */
    private const string MESSAGE_ERROR_INCORRECT_DATA = 'Epic sadface: Username and password do not match any user in this service';
    
    /**
     * @var string Ошибка при авторизации под заблокированным пользователем.
     */
    private const string MESSAGE_ERROR_LOCKED_USER = 'Epic sadface: Sorry, this user has been locked out.';
    
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
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * Заполняет поле для ввода логина.
     *
     * @param string $username Логин пользователя.
     *
     * @return void
     *
     * @throws AssertionEmptyFailed
     */
    final public function fillUsername(string $username): void
    {
        $this->assertFieldEmpty(self::FIELD_USERNAME_XPATH);
        $this->fillField(self::FIELD_USERNAME_XPATH, $username);
    }
    
    /**
     * Заполняет поле для ввода пароля.
     *
     * @param string $password Пароль пользователя.
     *
     * @return void
     *
     * @throws AssertionEmptyFailed
     */
    final public function fillPassword(string $password): void
    {
        $this->assertFieldEmpty(self::FIELD_PASSWORD_XPATH);
        $this->fillField(self::FIELD_PASSWORD_XPATH, $password);
    }
    
    /**
     * Нажимает на кнопку входа в систему.
     *
     * @return void
     *
     * @throws InvalidDataForm Если валидация формы авторизации не прошла успешно.
     */
    final public function clickButtonLogin(): void
    {
        $this->clickButton(self::BUTTON_LOGIN_XPATH);
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненном поле логина.
     *
     * @return string
     */
    final public function getMessageRequiredUsername(): string
    {
        return self::MESSAGE_ERROR_REQUIRED_USERNAME;
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненном поле пароля.
     *
     * @return string
     */
    final public function getMessageRequiredPassword(): string
    {
        return self::MESSAGE_ERROR_REQUIRED_PASSWORD;
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
}
