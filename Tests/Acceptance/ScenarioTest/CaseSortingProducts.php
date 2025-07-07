<?php

declare(strict_types=1);


namespace Tests\Acceptance\ScenarioTest;


use Codeception\Example;
use Tests\Acceptance\ScenarioTest\Traits\LoginStepsTrait;
use InvalidArgumentException;
use Tests\Support\Step\Acceptance\InventorySteps;

/**
 * Общий тестовый сценарий для проверки сортировки товаров.
 *
 * Сценарий включает в себя:
 * 1. Использование трейта для выполнения шагов, связанных с авторизацией.
 * 2. DataProvider для получения режимов сортировки.
 * 3. Метод `testSorting`, который принимает массив режимов сортировки и проверяет их корректность.
 *
 */
abstract class CaseSortingProducts
{
    use LoginStepsTrait;
    
    /**
     * Выполняет тестирование сортировки товаров для определенного типа пользователя.
     *
     * Метод реализует следующий сценарий:
     * 1. Выполняет вход в систему под определенным типом пользователя.
     * 2. Проверяет работу сортировки товаров для всех переданных режимов.
     *
     * @param Example $example Тестовые данные с ключом, содержащим массив режимов сортировки:
     * - Name (A to Z) - сортировка в алфавитном порядке по возрастанию
     * - Name (Z to A) - сортировка в алфавитном порядке по убыванию
     * - Price (low to high) - сортировка по возрастанию цены
     * - Price (high to low) - сортировка по убыванию цены
     *
     * @return void
     */
    abstract public function tryTotest(Example $example): void;
    
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