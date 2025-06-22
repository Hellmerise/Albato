<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use InvalidArgumentException;
use Tests\Support\Config\InventoryColumnEnum;
use Tests\Support\Step\Interfaces\InventoryInterface;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\InventorySortingEnum;
use Tests\Support\Data\Acceptance\Users;
use Tests\Support\Page\Acceptance\InventoryPage;
use ValueError;

class InventorySteps extends LoginSteps implements InventoryInterface
{
    private readonly InventoryPage $inventoryPage;
    private readonly string        $select_for_sorting;
    
    public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario, $acceptanceTester);
        $this->inventoryPage = new InventoryPage($acceptanceTester);
        $this->select_for_sorting = $this->inventoryPage->returnSelectSort();
    }
    
    final public function loginAsStandardUser(): void
    {
        $this->signIn(Users::STANDARD);
    }
    
    final public function loginAsLockedOutUser(): void
    {
        $this->signIn(Users::LOCKED_OUT);
    }
    
    final public function loginAsProblemUser(): void
    {
        $this->signIn(Users::PROBLEM);
    }
    
    final public function loginAsPerformanceGlitchUser(): void
    {
        $this->signIn(Users::PERFORMANCE_GLITCH);
    }
    
    final public function loginAsErrorUser(): void
    {
        $this->signIn(Users::ERROR);
    }
    
    final public function loginAsVisualUser(): void
    {
        $this->signIn(Users::VISUAL);
    }
    
    final public function checkItemsSorting(string $modeSort): void
    {
        $this->inventoryPage->checkTitlePage();
        
        try {
            $sortEnum = InventorySortingEnum::from($modeSort);
            $this->selectOption($this->select_for_sorting, $sortEnum->value);
            $this->waitForPageLoad();
            $items = $this->inventoryPage->returnListProductsFromPage();
            
            if (empty($items)) {
                throw new InvalidArgumentException('Нет товаров для проверки сортировки');
            }
            
            $sortOptions = $sortEnum->getSortOptions();
            
            $this->assertSorting(
                $items,
                $sortOptions[InventorySortingEnum::COLUMN],
                $sortOptions[InventorySortingEnum::ASCENDING],
                $sortOptions[InventorySortingEnum::MESSAGE]
            );
        } catch (ValueError $e) {
            throw new InvalidArgumentException('Неверный режим сортировки: ' . $modeSort);
        }
    }
    
    final public function clickButtonCart(): void
    {
        $this->inventoryPage->checkTitlePage();
        
        $this->safeClick($this->inventoryPage->returnButtonCart());
        
        $this->inventoryPage->waitForPageNotVisible();
    }
    
    final public function fillCart(): void
    {
        $this->inventoryPage->checkTitlePage();
        
        $items = $this->inventoryPage->returnListProductsFromPage();
        
        if (empty($items)) {
            throw new InvalidArgumentException('Нет товаров для добавления в корзину');
        }
        
        $randomCount = mt_rand(1, count($items));
        $selectedItems = array_rand($items, $randomCount);
        
        $selectedIndices = is_array($selectedItems) ? $selectedItems : [$selectedItems];
        
        foreach ($selectedIndices as $index) {
            $this->addToCart($items[$index][InventoryColumnEnum::NAME->value]);
        }
    }
    
    final protected function removeFromCart(string $itemName): void
    {
        $this->addToCart($itemName);
    }
    
    private function addToCart(string $itemName): void
    {
        $buttonXPath = sprintf(
            "//div[text() = '%s']/ancestor::div[@data-test = '%s']//child::button",
            $itemName,
            $this->inventoryPage->returnAttributeDataTestForItems(),
        );
        $this->safeClick($buttonXPath);
    }
    
    private function signIn(Users $user): void
    {
        $this->login($user->value, Users::PASSWORD);
        $this->inventoryPage->waitForPageVisible();
        $this->inventoryPage->checkTitlePage();
    }
    
    private function assertSorting(array $items, string $column, bool $ascending, string $errorMessage): void
    {
        $values = array_column($items, $column);
        
        $sortedValues = $values;
        
        $sortType = $column === InventoryColumnEnum::NAME->value ? SORT_STRING : SORT_NUMERIC;
        
        $sortFunction = $ascending ? 'sort' : 'rsort';
        
        $sortFunction($sortedValues, $sortType);
        
        $this->assertEquals($sortedValues, $values, $errorMessage);
    }
}
