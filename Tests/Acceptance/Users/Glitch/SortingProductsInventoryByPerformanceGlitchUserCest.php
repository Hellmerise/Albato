<?php

declare(strict_types=1);


namespace Tests\Acceptance\Users\Glitch;


use Tests\Acceptance\ScenarioTest\CaseSortingProducts;
use Codeception\Attribute\Group;
use Codeception\Example;


#[Group('first')]
final class SortingProductsInventoryByPerformanceGlitchUserCest extends CaseSortingProducts
{
    /**
     * @dataProvider dataProvider
     */
    public function tryToTest(Example $example): void
    {
        $this->loginSteps->loginAsPerformanceGlitchUser();
        
        $this->testSorting($example['modeSort']);
    }
}
