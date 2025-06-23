<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use Tests\Support\Page\Interfaces\ListPageInterface;

abstract class AbstractListPage extends AbstractMenuPage implements ListPageInterface
{
    private const string XPATH_TEMPLATE = "//div[@data-test='%s']/child::div[@data-test='%s']";
    protected static string $DATA_TEST_NAME_ITEM = "inventory-item";
    
    abstract protected string $data_test_name_list {
        get;
    }
    
    abstract protected array $item_selectors {
        get;
    }
    
    final public function returnListProductsFromPage(): array
    {
        $xpath = $this->buildXPath();
        
        if (!static::$acceptanceTester->tryToSeeElement($xpath)) {
            return [];
        }
        
        $totalItems = $this->countTotalItems($xpath);
        
        return $this->mapItemsData($totalItems);
    }
    
    final public function returnAttributeDataTestForItems(): string
    {
        return static::$DATA_TEST_NAME_ITEM;
    }
    
    private function buildXPath(): string
    {
        return sprintf(
            self::XPATH_TEMPLATE,
            $this->data_test_name_list,
            static::$DATA_TEST_NAME_ITEM
        );
    }
    
    private function mapItemsData(int $totalItems): array
    {
        return array_map(
            fn(int $index) => $this->getItemData($index),
            range(1, $totalItems)
        );
    }
    
    private function getItemData(int $index): array
    {
        return array_map(
            fn(string $xpath) => $this->extractFormattedValue($xpath, $index),
            $this->item_selectors
        );
    }
    
    private function extractFormattedValue(string $xpath, int $index): float|string|int
    {
        $value = static::$acceptanceTester->grabTextFrom(
            sprintf("%s[%d]%s", $this->buildXPath(), $index, $xpath)
        );
        
        return $this->formatValue($value);
    }
    
    private function formatValue(string $value): float|string|int
    {
        return match (true) {
            str_starts_with($value, '$') => static::$acceptanceTester->grabPriceFrom($value, '$'),
            is_numeric($value) => (int)$value,
            default => $value
        };
    }
    
    private function countTotalItems(string $xpath): int
    {
        return count(static::$acceptanceTester->grabMultiple($xpath));
    }
}