<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Problem;


use Tests\Acceptance\ScenarioTest\CaseSortingProducts;
use Codeception\Attribute\Group;
use Codeception\Example;


#[Group('first')]
final class SortingProductsInventoryByProblemUserCest extends CaseSortingProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsProblemUser();
        
        $this->testSorting($example['modeSort']);
    }
}