<?php

declare(strict_types=1);

namespace Tests\Acceptance\NoAuth;


use Codeception\Attribute\Group;
use Tests\Acceptance\ScenarioTest\CaseAccessToPages;
use Tests\Support\AcceptanceTester;


#[Group('second')]
final class AccessWithoutAuthorizationCest extends CaseAccessToPages
{
    public function testAccessWithoutLogged(AcceptanceTester $I): void
    {
        $I->wantTo('Проверить доступ к различным страницам без авторизации');
        $this->testAccessToPagesWithoutAuthorization();
    }
}
