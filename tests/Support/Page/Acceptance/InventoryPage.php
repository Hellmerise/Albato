<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\Config\InventoryColumnEnum;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractListPage;

final class InventoryPage extends AbstractListPage
{
    private const string DATA_TEST_NAME_LIST = "inventory-list";
    private const string INVENTORY_LIST      = "//div[@data-test = '" . self::DATA_TEST_NAME_LIST . "']";
    private const string SELECT_SORT         = "//select[@data-test = 'product-sort-container']";
    protected static string $title = "Products";
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    final public function returnSelectSort(): string
    {
        return self::SELECT_SORT;
    }
    
    final protected string $data_test_name_list {
        get {
            return self::DATA_TEST_NAME_LIST;
        }
    }
    final protected array  $item_selectors {
        get {
            return [
                InventoryColumnEnum::IMG_LINK->value    => InventoryColumnEnum::IMG_LINK->getXPath(),
                InventoryColumnEnum::NAME->value        => InventoryColumnEnum::NAME->getXPath(),
                InventoryColumnEnum::NAME_LINK->value   => InventoryColumnEnum::NAME_LINK->getXPath(),
                InventoryColumnEnum::DESCRIPTION->value => InventoryColumnEnum::DESCRIPTION->getXPath(),
                InventoryColumnEnum::PRICE->value       => InventoryColumnEnum::PRICE->getXPath(),
            ];
        }
    }
    
    final protected function returnElementsForWait(): array
    {
        return [
            self::INVENTORY_LIST,
            self::SELECT_SORT,
        ];
    }
}
