<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Error;


use Tests\Acceptance\ScenarioTest\CaseSortingProducts;
use Codeception\Attribute\Group;
use Codeception\Example;


#[Group('first')]
final class SortingProductsInventoryByErrorUserCest extends CaseSortingProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsErrorUser();
        
        $this->testSorting($example['modeSort']);
    }
}
