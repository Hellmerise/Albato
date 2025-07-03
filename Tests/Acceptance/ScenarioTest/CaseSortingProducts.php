<?php

declare(strict_types=1);


namespace Tests\Acceptance\ScenarioTest;


use Tests\Acceptance\ScenarioTest\Traits\LoginStepsTrait;
use InvalidArgumentException;
use Tests\Support\Step\Acceptance\InventorySteps;

abstract class CaseSortingProducts
{
    use LoginStepsTrait;
    
    /**
     * Проверяет корректность сортировки товаров по заданным режимам.
     *
     * Метод принимает массив режимов сортировки, для каждого из которых
     * выполняет проверку сортировки с использованием шагов инвентаря.
     * Если массив режимов пуст, выбрасывается исключение.
     *
     * @param string[] $modes Массив режимов сортировки.
     *
     * Примеры входных данных:
     * ```
     * [
     *      'Name (A to Z)',
     *      'Name (Z to A)',
     *      'Price (low to high)',
     * ]
     * ```
     * или
     * ```
     * [
     *      'Price (high to low)',
     * ]
     * ```
     *
     * @return void
     *
     * @throws InvalidArgumentException Если массив режимов пуст.
     */
    final protected function testSorting(array $modes): void
    {
        if (empty($modes)) {
            throw new InvalidArgumentException(__METHOD__ . ': не передан ни один режим сортировки');
        }
        
        $inventorySteps = new InventorySteps($this->scenario, $this->acceptanceTester);
        
        foreach ($modes as $mode) {
            $this->acceptanceTester->comment("Проверяю режим сортировки: '$mode'");
            $inventorySteps->checkItemsSorting($mode);
        }
    }
    
    /**
     * @dataProvider dataProvider
     */
    final protected function dataProvider(): array
    {
        return require dirname(__DIR__, 2) . '/Support/Data/Acceptance/testcases_for_sorting_inventory.php';
    }
}