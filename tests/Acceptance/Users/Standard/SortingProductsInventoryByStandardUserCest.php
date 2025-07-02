<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Standard;


use Tests\Acceptance\ScenarioTest\CaseSortingProducts;
use Codeception\Attribute\Group;
use Codeception\Example;


#[Group('first')]
final class SortingProductsInventoryByStandardUserCest extends CaseSortingProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsStandardUser();
        
        $this->testSorting($example['modeSort']);
    }
}
