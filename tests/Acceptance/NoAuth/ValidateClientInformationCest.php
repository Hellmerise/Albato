<?php

declare(strict_types=1);


namespace Tests\Acceptance\NoAuth;


use Codeception\Attribute\Group;
use Tests\Acceptance\ScenarioTest\CaseValidationInformation;
use Tests\Support\AcceptanceTester;

#[Group('third')]
final class ValidateClientInformationCest extends CaseValidationInformation
{
    public function tryToTest(AcceptanceTester $I): void
    {
        $I->wantTo("Проверить валидацию формы путем заполнения разными вариантами");
        $this->testValidationOfInformationFields();
    }
}
