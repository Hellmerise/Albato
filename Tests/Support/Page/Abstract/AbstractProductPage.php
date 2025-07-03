<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use Codeception\Exception\TestRuntimeException;


abstract class AbstractProductPage extends AbstractMenuPage
{
    /**
     * Типы контейнеров
     */
    private const string DATA_TEST_CONTAINER_INVENTORY_ITEM = 'inventory-item';
    private const string DATA_TEST_CONTAINER_INVENTORY      = 'inventory-list';
    private const string DATA_TEST_CONTAINER_CART           = 'cart-list';
    private const string CONTAINER_XPATH                    = "//div[@data-test = '%s']";
    
    /**
     * Ключи для свойств товара.
     */
    private const string KEY_NAME        = 'name';
    private const string KEY_PRICE       = 'price';
    private const string KEY_ID          = 'id';
    private const string KEY_IMG_SRC     = 'img_src';
    private const string KEY_DESCRIPTION = 'description';
    private const string KEY_QUANTITY    = 'quantity';
    private const string KEY_IMG_LINK    = 'img_link';
    private const string KEY_NAME_LINK   = 'name_link';
    private const string KEY_BUTTON      = 'button';
    
    /**
     * Базовые Xpath выражения для свойств продукта.
     */
    private const string  ITEM_XPATH        = "/child::div[@data-test = 'inventory-item']";
    private const string  QUANTITY_XPATH    = "//child::div[@data-test = 'item-quantity']";
    private const string  IMG_XPATH         = "//child::img[contains(@data-test, 'item')]";
    private const string  NAME_XPATH        = "//child::div[@data-test = 'inventory-item-name']";
    private const string  DESCRIPTION_XPATH = "//child::div[@data-test = 'inventory-item-desc']";
    private const string  PRICE_XPATH       = "//child::div[@data-test = 'inventory-item-price']";
    private const string  IMG_LINK_XPATH    = self::IMG_XPATH . '/parent::a';
    private const string  NAME_LINK_XPATH   = self::NAME_XPATH . '/parent::a';
    private const string  BUTTON_XPATH      = self::PRICE_XPATH . '/following-sibling::button';
    
    /**
     * Указание соответствия между ключами и Xpath выражениями для извлечения значений.
     */
    private const array XPATH_MAPPING = [
        self::KEY_ID          => self::NAME_LINK_XPATH,
        self::KEY_IMG_SRC     => self::IMG_XPATH,
        self::KEY_NAME        => self::NAME_XPATH,
        self::KEY_DESCRIPTION => self::DESCRIPTION_XPATH,
        self::KEY_PRICE       => self::PRICE_XPATH,
        self::KEY_QUANTITY    => self::QUANTITY_XPATH,
        self::KEY_IMG_LINK    => self::IMG_LINK_XPATH,
        self::KEY_NAME_LINK   => self::NAME_LINK_XPATH,
        self::KEY_BUTTON      => self::BUTTON_XPATH,
    ];
    
    /**
     * @var string Валюта.
     */
    private const string PRICE_CURRENCY = "$";
    
    /**
     * @var string Xpath кнопки для возврата к странице товаров.
     */
    private const string BACK_TO_PRODUCTS = "//button[@id = 'back-to-products']";
    
    /**
     * @var string Шаблон Xpath выражения для контейнера списка.
     */
    abstract protected string $container_pattern_xpath {
        get;
    }
    
    /**
     * @var string Значение data-test для контейнера списка.
     */
    abstract protected string $container_data_test {
        get;
    }
    
    /**
     * Получает список продуктов со страницы.
     *
     * @return array
     */
    public function getProductsFromPage(): array
    {
        $items = $this->extractProducts();
        
        if (!empty($items)) {
            $this->assertUniqueValuesByContainer($items);
        }
        
        return $items;
    }
    
    /**
     * Получает количество товаров
     */
    final public function getCountProductsOnPage(): int
    {
        return count(static::$acceptanceTester->grabMultiple($this->buildListXPath()));
    }
    
    /**
     * Название ключа, который хранит в себе значение для названия товара.
     *
     * @return string
     */
    final public function getKeyId(): string
    {
        return self::KEY_ID;
    }
    
    /**
     * Название ключа, который хранит в себе значение для названия товара.
     *
     * @return string
     */
    final public function getKeyName(): string
    {
        return self::KEY_NAME;
    }
    
    /**
     * Название ключа, который хранит в себе значение для цены товара.
     *
     * @return string
     */
    final public function getKeyPrice(): string
    {
        return self::KEY_PRICE;
    }
    
    /**
     * Название ключа, который хранит в себе Xpath кнопки для товара.
     *
     * @return string
     */
    final public function getKeyButton(): string
    {
        return self::KEY_BUTTON;
    }
    
    /**
     * Название ключа, который хранит в себе Xpath описания товара.
     *
     * @return string
     */
    final public function getKeyDescription(): string
    {
        return self::KEY_DESCRIPTION;
    }
    
    final static protected function getPatternContainerXpath(): string
    {
        return self::CONTAINER_XPATH;
    }
    
    final static protected function getContainerInventoryList(): string
    {
        return self::DATA_TEST_CONTAINER_INVENTORY;
    }
    
    final static protected function getContainerInventoryItem(): string
    {
        return self::DATA_TEST_CONTAINER_INVENTORY_ITEM;
    }
    
    final static protected function getContainerCartList(): string
    {
        return self::DATA_TEST_CONTAINER_CART;
    }
    
    private function assertUniqueValuesByContainer(array $items): void
    {
        switch ($this->container_data_test) {
            case self::DATA_TEST_CONTAINER_INVENTORY:
                static::$acceptanceTester->assertUniqueValue($items, self::KEY_ID, self::KEY_NAME, self::KEY_DESCRIPTION, self::KEY_IMG_SRC);
                break;
            case self::DATA_TEST_CONTAINER_CART:
                static::$acceptanceTester->assertUniqueValue($items, self::KEY_ID, self::KEY_NAME, self::KEY_DESCRIPTION);
                break;
            default:
                throw new TestRuntimeException("Неизвестное название контейнера: '$this->container_data_test'");
        }
    }
    
    private function extractProducts(): array
    {
        $baseKeys = [
            self::KEY_ID,
            self::KEY_NAME,
            self::KEY_DESCRIPTION,
            self::KEY_PRICE,
            self::KEY_BUTTON,
        ];
        
        $keys = match ($this->container_data_test) {
            self::DATA_TEST_CONTAINER_INVENTORY_ITEM => [
                self::KEY_IMG_SRC,
            ],
            self::DATA_TEST_CONTAINER_INVENTORY => [
                self::KEY_IMG_SRC,
                self::KEY_IMG_LINK,
                self::KEY_NAME_LINK,
            ],
            self::DATA_TEST_CONTAINER_CART => [
                self::KEY_QUANTITY,
                self::KEY_NAME_LINK,
            ],
            default => throw new TestRuntimeException("Неизвестное название контейнера: '$this->container_data_test'")
        };
        
        return $this->extractItems(array_merge($baseKeys, $keys));
    }
    
    private function extractItems(array $keys): array
    {
        $count = $this->getCountProductsOnPage();
        if ($count === 0) {
            return [];
        }
        
        return array_map(
            fn(int $index) => $this->extractItemData($keys, $index),
            range(1, $count)
        );
    }
    
    private function extractItemData(array $keys, int $index): array
    {
        $data = [];
        foreach ($keys as $key) {
            $xpath = $this->buildElementXPath(self::XPATH_MAPPING[$key], $index);
            $value = $this->extractElementValue($xpath, $key);
            $data[$key] = $this->formatValue($value, $key);
        }
        
        return $data;
    }
    
    /**
     * Строит Xpath выражение для списка товаров.
     *
     * @return string
     *
     * @example //div[@data-test = 'inventory-item']
     */
    private function buildListXPath(): string
    {
        return match ($this->container_data_test) {
            self::DATA_TEST_CONTAINER_INVENTORY_ITEM => $this->container_pattern_xpath,
            default => sprintf(
                '%s%s',
                $this->container_pattern_xpath,
                self::ITEM_XPATH
            )
        };
    }
    
    /**
     * Формирует полный XPath для элемента
     */
    private function buildElementXPath(string $selector, int $index): string
    {
        return sprintf(
            '%s[%d]%s',
            $this->buildListXPath(),
            $index,
            $selector
        );
    }
    
    /**
     * Извлекает значение элемента
     */
    private function extractElementValue(string $xpath, string $key): string
    {
        return match ($key) {
            self::KEY_IMG_SRC => static::$acceptanceTester->grabAttributeFrom($xpath, 'src'),
            self::KEY_ID => match ($this->container_data_test) {
                self::DATA_TEST_CONTAINER_INVENTORY_ITEM => $this->extractIdFromUrl(),
                self::DATA_TEST_CONTAINER_INVENTORY, self::DATA_TEST_CONTAINER_CART => $this->extractIdFromAttribute($xpath),
                default => throw new TestRuntimeException("Неизвестное название контейнера: '$this->container_data_test'")
            },
            self::KEY_NAME_LINK, self::KEY_IMG_LINK, self::KEY_BUTTON => $xpath,
            default => static::$acceptanceTester->grabTextFrom($xpath)
        };
    }
    
    /**
     * Форматирует значение в нужный тип
     */
    private function formatValue(string $value, string $key): string|int|float
    {
        return match (true) {
            $key === self::KEY_PRICE => static::$acceptanceTester->extractFloatFrom($value),
            $key === self::KEY_ID || $key === self::KEY_QUANTITY => (int)$value,
            default => $value
        };
    }
    
    private function extractIdFromUrl(): string
    {
        $url = static::$acceptanceTester->grabFromCurrentUrl();
        parse_str(parse_url($url, PHP_URL_QUERY) ? : '', $params);
        
        if (!isset($params['id'])) {
            throw new TestRuntimeException('ID не найден в URL');
        }
        
        return $params['id'];
    }
    
    private function extractIdFromAttribute(string $xpath): string
    {
        $id = static::$acceptanceTester->grabAttributeFrom($xpath, 'id');
        
        if (!preg_match('/item_(\d+)_/', $id, $matches)) {
            throw new TestRuntimeException("Не удалось извлечь ID из: '$id'");
        }
        
        return $matches[1];
    }
}