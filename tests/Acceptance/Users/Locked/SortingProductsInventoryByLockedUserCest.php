<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Locked;


use Tests\Acceptance\ScenarioTest\CaseSortingProducts;
use Codeception\Attribute\Group;
use Codeception\Example;


#[Group('first')]
final class SortingProductsInventoryByLockedUserCest extends CaseSortingProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsLockedUser();
        
        $this->testSorting($example['modeSort']);
    }
}
