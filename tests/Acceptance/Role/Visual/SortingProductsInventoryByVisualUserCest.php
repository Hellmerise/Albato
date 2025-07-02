<?php

declare(strict_types=1);


namespace Tests\Acceptance\Role\Visual;


use Tests\Acceptance\ScenarioTest\CaseSortingProducts;
use Codeception\Attribute\Group;
use Codeception\Example;


#[Group('first')]
final class SortingProductsInventoryByVisualUserCest extends CaseSortingProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsVisualUser();
        
        $this->testSorting($example['modeSort']);
    }
}
