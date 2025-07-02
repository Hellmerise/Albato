<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use InvalidArgumentException;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\TestCasesEnum;
use Tests\Support\Page\Abstract\AbstractProductPage;

/**
 * Страница со списком товаров.
 * Предоставляет методы для получения селекторов страницы,
 * включая элемент для сортировки товаров.
 */
final class InventoryPage extends AbstractProductPage
{
    private const string URL                      = '/inventory.html';
    private const string TITLE                    = 'Products';
    private const string SELECT_FOR_SORTING_XPATH = "//select[@data-test = 'product-sort-container']";
    
    /**
     * @inheritDoc
     */
    protected string $url {
        get {
            return self::URL;
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected string $title {
        get {
            return self::TITLE;
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected array $wait_elements {
        get {
            return [
                $this->container_pattern_xpath,
                self::SELECT_FOR_SORTING_XPATH,
            ];
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $container_data_test {
        get {
            return parent::getContainerInventoryList();
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected string $container_pattern_xpath {
        get {
            return sprintf(parent::getPatternContainerXpath(), $this->container_data_test);
        }
    }
    
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * Выбирает значение в select.
     *
     * @param string $valueOption Значение.
     *
     * @return string Название отсортированного свойства.
     */
    final public function sortBy(string $valueOption): string
    {
        $this->selectOption(self::SELECT_FOR_SORTING_XPATH, $valueOption);
        
        return match ($valueOption) {
            TestCasesEnum::VALUE_SORT_NAME_ASC, TestCasesEnum::VALUE_SORT_NAME_DESC => $this->getKeyName(),
            TestCasesEnum::VALUE_SORT_PRICE_ASC, TestCasesEnum::VALUE_SORT_PRICE_DESC => $this->getKeyPrice(),
            default => throw new InvalidArgumentException("Неизвестный режим сортировки: '$valueOption'"),
        };
    }
}
