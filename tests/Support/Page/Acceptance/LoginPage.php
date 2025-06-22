<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractPage;

final class LoginPage extends AbstractPage
{
    private const string FIELD_USERNAME_XPATH = "//input[@data-test = 'username']";
    private const string FIELD_PASSWORD_XPATH = "//input[@data-test = 'password']";
    private const string BUTTON_LOGIN_XPATH   = "//input[@data-test = 'login-button']";
    private const string ERROR_MESSAGE_XPATH  = "//h3[@data-test = 'error']";
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * @return string XPath для поля ввода имени пользователя.
     */
    final public function returnFieldUsername(): string
    {
        return self::FIELD_USERNAME_XPATH;
    }
    
    /**
     * @return string XPath для поля ввода пароля.
     */
    final public function returnFieldPassword(): string
    {
        return self::FIELD_PASSWORD_XPATH;
    }
    
    /**
     * @return string XPath для кнопки входа.
     */
    final public function returnButtonLogin(): string
    {
        return self::BUTTON_LOGIN_XPATH;
    }
    
    final public function returnErrorMessage(): string
    {
        return $this->getErrorMessageOrFail(self::ERROR_MESSAGE_XPATH);
    }
    
    final protected function returnElementsForWait(): array
    {
        return [
            self::FIELD_USERNAME_XPATH,
            self::FIELD_PASSWORD_XPATH,
            self::BUTTON_LOGIN_XPATH,
        ];
    }
}
