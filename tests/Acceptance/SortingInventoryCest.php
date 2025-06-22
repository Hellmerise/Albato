<?php

declare(strict_types=1);


namespace Tests\Acceptance;


use Codeception\Attribute\Group;
use Codeception\Example;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Step\Acceptance\InventorySteps;

#[Group('first')]
final class SortingInventoryCest
{
    private InventorySteps $inventorySteps;
    
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->inventorySteps = new InventorySteps($scenario, $I);
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function testSorting(Scenario $scenario, AcceptanceTester $I, Example $example): void
    {
        $I->wantTo($example['wantTo']);
        $this->inventorySteps->loginAsStandardUser();
        $this->inventorySteps->checkItemsSorting($example['mode']);
        $I->wait(4);
    }
    
    private function dataProvider(): array
    {
        return require dirname(__DIR__) . '/Support/Data/Acceptance/testcases_for_sorting_inventory.php';
    }
}
