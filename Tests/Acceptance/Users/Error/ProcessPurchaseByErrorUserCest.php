<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Error;


use Codeception\Attribute\Group;
use Codeception\Example;
use Tests\Acceptance\ScenarioTest\CasePurchaseProducts;

#[Group('third')]
final class ProcessPurchaseByErrorUserCest extends CasePurchaseProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsErrorUser();
        $this->testPurchaseProducts(
            $example[parent::KEY_COUNT] ?? null,
            $example[parent::KEY_FIRSTNAME] ?? null,
            $example[parent::KEY_LASTNAME] ?? null,
            $example[parent::KEY_POSTAL_CODE] ?? null
        );
    }
}
