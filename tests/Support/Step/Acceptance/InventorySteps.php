<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Exception\TestRuntimeException;
use Codeception\Scenario;
use InvalidArgumentException;
use Tests\Support\Config\TestCasesEnum;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\InventoryPage;

final class InventorySteps extends AcceptanceTester
{
    private readonly InventoryPage $inventoryPage;
    private readonly int           $countProducts;
    private readonly string        $keyButton;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        
        $this->inventoryPage = new InventoryPage($acceptanceTester);
        
        $this->countProducts = $this->inventoryPage->getCountProductsOnPage();
        $this->keyButton = $this->inventoryPage->getKeyButton();
    }
    
    final public function clickButtonCart(): void
    {
        $this->inventoryPage->assertHeaderPage();
        
        $this->inventoryPage->clickButtonCart();
        
        $this->inventoryPage->waitForPageNotVisible();
    }
    
    /**
     * Отсортировать список товаров на главной странице.
     *
     * @param string $modeSort Режим сортировки.
     *
     * Варианты:
     * - `Price (low to high)` - сортировка по возрастанию цены (low to high);
     * - `Price (high to low)` - сортировка по убыванию цены (high to low);
     * - `Name (A to Z)` - сортировка по названию в порядке возрастания (A to Z);
     * - `Name (Z to A)` - сортировка по названию в порядке убывания (Z to A);
     *
     * @return void
     */
    final public function checkItemsSorting(string $modeSort): void
    {
        $this->inventoryPage->assertHeaderPage();
        
        if ($this->countProducts <= 1) {
            $this->comment('Проверка сортировки пропущена, так как на странице не больше одного товара');
            
            return;
        }
        
        $sortingConfig = [
            TestCasesEnum::VALUE_SORT_NAME_ASC => [
                'ascending' => true,
                'message'   => 'Названия товаров не отсортированы в алфавитном порядке по возрастанию',
            ],
            TestCasesEnum::VALUE_SORT_NAME_DESC => [
                'ascending' => false,
                'message'   => 'Названия товаров не отсортированы в алфавитном порядке по убыванию',
            ],
            TestCasesEnum::VALUE_SORT_PRICE_ASC => [
                'ascending' => true,
                'message'   => 'Названия товаров не отсортированы по возрастанию цены',
            ],
            TestCasesEnum::VALUE_SORT_PRICE_DESC => [
                'ascending' => false,
                'message'   => 'Названия товаров не отсортированы по убыванию цены',
            ],
        ];
        
        if (!isset($sortingConfig[$modeSort])) {
            throw new InvalidArgumentException(__METHOD__ . ": неизвестный параметр для сортировки - '$modeSort'");
        }
        
        $sortedKey = $this->inventoryPage->sortBy($modeSort);
        $sortedProducts = $this->inventoryPage->getProductsFromPage();
        
        $items = array_column($sortedProducts, $sortedKey);
        
        $this->assertSorting(
            $items,
            $sortingConfig[$modeSort]['ascending'],
            $sortingConfig[$modeSort]['message']
        );
    }
    
    /**
     * Добавляет случайное количество товаров в корзину и возвращает список добавленных товаров.
     *
     * Алгоритм работы:
     * 1. Проверяет наличие заголовка страницы с товарами.
     * 2. Проверяет, что переданное количество товаров для добавления не меньше нуля (если указано).
     * 3. Проверяет, что на странице есть доступные товары для добавления.
     * 4. Получает список всех товаров с текущей страницы.
     * 5. Определяет максимальное количество добавляемых товаров:
     *    - если параметр $count не задан, максимальное количество равно общему числу товаров;
     *    - иначе — минимальное из $count и общего числа товаров.
     * 6. Случайным образом выбирает число товаров для добавления от 1 до максимального.
     * 7. С помощью array_rand выбирает случайные ключи товаров из списка.
     * 8. Для каждого выбранного товара вызывает метод клика по кнопке добавления в корзину.
     * 9. Собирает массив добавленных товаров.
     * 10. Получает текущее количество товаров в корзине и проверяет, что оно совпадает с количеством добавленных.
     * 11. Возвращает массив добавленных товаров.
     *
     * @param int|null $count Максимальное количество товаров для добавления.
     *                        Если null — добавляет случайное количество от 1 до общего числа товаров.
     *
     * @return array Массив добавленных товаров.
     *
     * @throws InvalidArgumentException Если передано отрицательное количество товаров.
     * @throws TestRuntimeException В случае отсутствия товаров для добавления или ошибок в работе с элементами страницы.
     */
    final public function addProductsInCart(?int $count = null): array
    {
        $this->inventoryPage->assertHeaderPage();
        
        //$this->inventoryPage->clickResetApp();
        
        if ($count === null || $count === 0) {
            return [];
        }
        
        if ($count < 0) {
            throw new InvalidArgumentException(__METHOD__ . 'Количество добавляемых товаров не может быть меньше нуля');
        }
        
        if ($this->countProducts <= 0) {
            $this->fail('Нет товаров для добавления в корзину.');
        }
        
        $listProducts = $this->inventoryPage->getProductsFromPage();
        
        if (empty($listProducts)) {
            throw new TestRuntimeException(__METHOD__ . 'Вернулся пустой список товаров.');
        }
        
        $maxCount = min($count, $this->countProducts);
        
        $randomKeys = array_rand($listProducts, $maxCount);
        $randomKeys = (array)$randomKeys;
        
        $selectedProducts = [];
        
        foreach ($randomKeys as $key) {
            if (!isset($listProducts[$key][$this->keyButton])) {
                throw new TestRuntimeException("Кнопка для добавления товара с индексом '$key' не найдена.");
            }
            
            $this->click($listProducts[$key][$this->keyButton]);
            $selectedProducts[] = $listProducts[$key];
        }
        
        $itemsInCart = $this->inventoryPage->getValueCart();
        
        $this->assertCount($itemsInCart, $selectedProducts);
        
        return $selectedProducts;
    }
    
    /**
     * Проверить сортировку элементов.
     *
     * @param array  $items     Список элементов.
     * @param bool   $ascending Сортировка по возрастанию? `true` = Да, иначе `false`.
     * @param string $message   Сообщение об ошибке.
     *
     * @return void
     */
    private function assertSorting(array $items, bool $ascending, string $message): void
    {
        $sortedValues = $items;
        
        $sortFunction = $ascending ? 'sort' : 'rsort';
        $sortType = isset($items[0]) && is_string($items[0]) ? SORT_STRING : SORT_NUMERIC;
        
        $sortFunction($sortedValues, $sortType);
        
        $this->assertEquals(
            $sortedValues,
            $items,
            $message
        );
    }
}


