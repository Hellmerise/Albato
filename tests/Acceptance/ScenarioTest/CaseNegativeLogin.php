<?php

declare(strict_types=1);

namespace Tests\Acceptance\ScenarioTest;


use Tests\Acceptance\ScenarioTest\Traits\LoginPageTrait;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;

abstract class CaseNegativeLogin
{
    use LoginPageTrait;
    
    private const string KEY_USERNAME = 'username';
    private const string KEY_PASSWORD = 'password';
    
    /**
     * Проверка негативных сценариев авторизации.
     *
     * Сценарии:
     * 1. Авторизация без логина учетной записи.
     * 2. Авторизация без пароля учетной записи.
     * 3. Авторизация с невалидной парой логина и пароля.
     * 4. Авторизация под учетной записью, которой запрещён доступ в систему.
     *
     * @return void
     */
    final protected function testLoginFailed(): void
    {
        $this->caseLoginWithoutUsername();
        $this->caseLoginWithoutPassword();
        $this->caseLoginIncorrectData();
        $this->caseLoginLockedUser();
    }
    
    /**
     * Авторизация без логина.
     *
     * Описаны два кейса:
     * - Попытка авторизации без логина и без пароля;
     * - Попытка авторизации без логина, но с каким-то паролем;
     *
     * @return void
     */
    private function caseLoginWithoutUsername(): void
    {
        $testCases = [
            [
                self::KEY_USERNAME => '',
                self::KEY_PASSWORD => '',
            ],
            [
                self::KEY_USERNAME => '',
                self::KEY_PASSWORD => 'secret_sauce',
            ],
        ];
        
        $message = $this->loginPage->getMessageRequiredUsername();
        
        foreach ($testCases as $testCase) {
            $this->signInUserData(
                $testCase[self::KEY_USERNAME],
                $testCase[self::KEY_PASSWORD],
                $message
            );
        }
    }
    
    /**
     * Авторизация без пароля.
     *
     * Описан один кейс:
     * - Попытка авторизации с существующим логином, но без пароля.
     *
     * @return void
     */
    private function caseLoginWithoutPassword(): void
    {
        $this->signInUserData(
            'standard_user',
            '',
            $this->loginPage->getMessageRequiredPassword(),
        );
    }
    
    /**
     * Авторизация с невалидными данными.
     *
     * Описаны три кейса:
     * - Попытка авторизации с существующим логином, но с несуществующим паролем.
     * - Попытка авторизации с несуществующим логином, но с существующим паролем.
     * - Попытка авторизации с несуществующими логином и паролем.
     *
     * @return void
     */
    private function caseLoginIncorrectData(): void
    {
        $testCases = [
            [
                self::KEY_USERNAME => 'standard_user',
                self::KEY_PASSWORD => '123456',
            ],
            [
                self::KEY_USERNAME => '123456',
                self::KEY_PASSWORD => 'secret_sauce',
            ],
            [
                self::KEY_USERNAME => '123456',
                self::KEY_PASSWORD => '123456',
            ],
        ];
        
        $message = $this->loginPage->getMessageIncorrectData();
        
        foreach ($testCases as $testCase) {
            $this->signInUserData(
                $testCase[self::KEY_USERNAME],
                $testCase[self::KEY_PASSWORD],
                $message
            );
        }
    }
    
    /**
     * Авторизация под заблокированным пользователем.
     *
     * @return void
     */
    private function caseLoginLockedUser(): void
    {
        $this->signInUserData(
            'locked_out_user',
            'secret_sauce',
            $this->loginPage->getMessageLockedUser(),
        );
    }
    
    /**
     * Метод авторизации с обработкой возникающих ошибок.
     *
     * @param string $username Логин учетной записи.
     * @param string $password Пароль учетной записи.
     * @param string $message  Ожидаемый текст ошибки на странице авторизации.
     *
     * @return void
     */
    private function signInUserData(string $username, string $password, string $message): void
    {
        // Переход на страницу авторизации
        $this->loginPage->goToPage();
        
        // Утверждение, что текст логотипа соответствует ожидаемому
        $this->loginPage->assertLogoPage();
        
        try {
            // Вводим логин и пароль
            $this->loginPage->fillUsername($username);
            $this->loginPage->fillPassword($password);
            
            // Нажимаем на кнопку "Login"
            $this->loginPage->clickButtonLogin();
        } catch (AssertionEmptyFailed $assertionException) {
            // Обрабатываем случай, когда при переходе на страницу авторизации
            // поля для ввода логина и пароля заполнены предустановленным значением.
            // Ожидается, что по умолчанию эти поля будут пустыми, а если нет, то завершаем тест неудачей.
            $this->acceptanceTester->fail($assertionException->getMessage());
        } catch (InvalidDataForm $invalidDataForm) {
            // Обрабатываем случай, когда после нажатия кнопки "Login"
            // происходит проверка пары "Логин:Пароль".
            // Ожидается, что ошибка отображаемая в UI будет соответствовать ожиданиям.
            $this->acceptanceTester->assertEquals(
                $message,
                $invalidDataForm->getMessageError(),
                'Тексты ошибок не совпадают'
            );
        }
    }
}