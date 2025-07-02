<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\Config\UsersEnum;
use Tests\Support\AcceptanceTester;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;
use Tests\Support\Page\Acceptance\LoginPage;


final class LoginSteps extends AcceptanceTester
{
    private readonly LoginPage $loginPage;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        
        $this->loginPage = new LoginPage($acceptanceTester);
    }
    
    /**
     * Вход в систему под стандартным пользователем.
     *
     * @return void
     */
    final public function loginAsStandardUser(): void
    {
        $this->login(UsersEnum::STANDARD);
    }
    
    /**
     * Вход в систему под заблокированным пользователем.
     *
     * @return void
     */
    final public function loginAsLockedUser(): void
    {
        $this->login(UsersEnum::LOCKED_OUT);
    }
    
    /**
     * Вход в систему под проблемным пользователем.
     *
     * @return void
     */
    final public function loginAsProblemUser(): void
    {
        $this->login(UsersEnum::PROBLEM);
    }
    
    /**
     * Вход в систему под пользователем с проблемами производительности.
     *
     * @return void
     */
    final public function loginAsPerformanceGlitchUser(): void
    {
        $this->login(UsersEnum::PERFORMANCE_GLITCH);
    }
    
    /**
     * Войти в систему под пользователем с ошибками.
     *
     * @return void
     */
    final public function loginAsErrorUser(): void
    {
        $this->login(UsersEnum::ERROR);
    }
    
    /**
     * Войти в систему под пользователем с визуальными глюками.
     *
     * @return void
     */
    final public function loginAsVisualUser(): void
    {
        $this->login(UsersEnum::VISUAL);
    }
    
    /**
     * Переходит на страницу авторизации.
     *
     * Заполняет форму авторизации.
     *
     * Выполняет попытку входа.
     *
     * @param UsersEnum $user
     *
     * @return void
     */
    private function login(UsersEnum $user): void
    {
        try {
            $this->loginPage->goToPage();
            $this->loginPage->assertLogoPage();
            $this->loginPage->fillUsername($user->value);
            $this->loginPage->fillPassword($user->getPassword());
            $this->loginPage->clickButtonLogin();
        } catch (AssertionEmptyFailed|InvalidDataForm $fail) {
            $this->fail($fail->getMessageError());
        }
        
        $this->loginPage->waitForPageNotVisible();
    }
}
