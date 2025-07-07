<?php

declare(strict_types=1);


namespace Tests\Acceptance\NoAuth;


use Codeception\Attribute\Group;
use Tests\Acceptance\ScenarioTest\CaseNegativeLogin;
use Tests\Support\AcceptanceTester;


#[Group('login')]
final class AuthorizationErrorsCest extends CaseNegativeLogin
{
    /**
     * Проверка возможных ошибок авторизации.
     *
     * @param AcceptanceTester $I
     *
     * @return void
     */
    public function tryToTest(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить авторизацию с некорректными данными входа");
        $this->testLoginFailed();
    }
}
