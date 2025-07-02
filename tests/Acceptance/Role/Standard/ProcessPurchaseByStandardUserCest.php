<?php

declare(strict_types=1);


namespace Tests\Acceptance\Role\Standard;


use Codeception\Attribute\Group;
use Codeception\Example;
use Tests\Acceptance\ScenarioTest\CasePurchaseProducts;
use Tests\Support\Config\TestCasesEnum;

#[Group('fourth', 'kek')]
final class ProcessPurchaseByStandardUserCest extends CasePurchaseProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsStandardUser();
        
        $this->testPurchaseProducts(
            $example[TestCasesEnum::KEY_COUNTS_PRODUCTS] ?? null,
            $example[TestCasesEnum::KEY_FIRSTNAME] ?? null,
            $example[TestCasesEnum::KEY_LASTNAME] ?? null,
            $example[TestCasesEnum::KEY_POSTAL_CODE] ?? null
        );
    }
}
