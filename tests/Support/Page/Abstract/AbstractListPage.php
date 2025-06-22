<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use Tests\Support\Page\Interfaces\ListPageInterface;

abstract class AbstractListPage extends AbstractMenuPage implements ListPageInterface
{
    protected static string $DATA_TEST_NAME_ITEM = "inventory-item";
    
    abstract protected string $data_test_name_list {
        get;
    }
    
    abstract protected array $item_selectors {
        get;
    }
    
    final public function returnListProductsFromPage(): array
    {
        return array_map(
            fn(int $index) => $this->returnItemData($index),
            range(1, $this->returnTotalItems())
        );
    }
    
    final public function returnAttributeDataTestForItems(): string
    {
        return static::$DATA_TEST_NAME_ITEM;
    }
    
    private function returnItemData(int $index): array
    {
        return array_map(
            fn(string $xpath) => $this->grabFormattedValue($xpath, $index),
            $this->item_selectors
        );
    }
    
    private function grabFormattedValue(string $xpath, int $index): float|string|int
    {
        $xpathParent = sprintf(
            "//div[@data-test='%s']/child::div[@data-test='%s']",
            $this->data_test_name_list,
            static::$DATA_TEST_NAME_ITEM
        );
        
        $value = static::$acceptanceTester->grabTextFrom(
            sprintf("%s[%d]%s", $xpathParent, $index, $xpath)
        );
        
        return match (true) {
            str_starts_with($value, '$') => static::$acceptanceTester->grabPriceFrom($value, '$'),
            is_numeric($value) => (int)$value,
            default => $value
        };
    }
    
    private function returnTotalItems(): int
    {
        return count(
            static::$acceptanceTester->grabMultiple(
                sprintf(
                    "//div[@data-test='%s']/child::div[@data-test='%s']",
                    $this->data_test_name_list,
                    static::$DATA_TEST_NAME_ITEM
                )
            )
        );
    }
}