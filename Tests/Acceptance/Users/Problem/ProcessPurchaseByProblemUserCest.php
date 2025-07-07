<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Problem;


use Codeception\Attribute\Group;
use Codeception\Example;
use Tests\Acceptance\ScenarioTest\CasePurchaseProducts;


#[Group('third')]
final class ProcessPurchaseByProblemUserCest extends CasePurchaseProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsProblemUser();
        $this->testPurchaseProducts(
            $example[parent::KEY_COUNT] ?? null,
            $example[parent::KEY_FIRSTNAME] ?? null,
            $example[parent::KEY_LASTNAME] ?? null,
            $example[parent::KEY_POSTAL_CODE] ?? null
        );
    }
}