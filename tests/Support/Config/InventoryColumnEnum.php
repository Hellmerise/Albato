<?php

declare(strict_types=1);

namespace Tests\Support\Config;


enum InventoryColumnEnum: string
{
    case QUANTITY = 'quantity';
    case IMG_LINK = 'img_link';
    case NAME = 'name';
    case NAME_LINK = 'name_link';
    case DESCRIPTION = 'description';
    case PRICE = 'price';
    
    private const string QUANTITY_XPATH    = "//div[@data-test = 'item-quantity']";
    private const string IMG_LINK_XPATH    = "//a[contains(@data-test, 'img-link')]";
    private const string NAME_LINK_XPATH   = "//a[contains(@data-test, 'title-link')]";
    private const string NAME_XPATH        = "//div[@data-test = 'inventory-item-name']";
    private const string DESCRIPTION_XPATH = "//div[@data-test = 'inventory-item-desc']";
    private const string PRICE_XPATH       = "//div[@data-test = 'inventory-item-price']";
    
    public function getXPath(): string
    {
        return match ($this) {
            self::QUANTITY => self::QUANTITY_XPATH,
            self::IMG_LINK => self::IMG_LINK_XPATH,
            self::NAME => self::NAME_XPATH,
            self::NAME_LINK => self::NAME_LINK_XPATH,
            self::DESCRIPTION => self::DESCRIPTION_XPATH,
            self::PRICE => self::PRICE_XPATH
        };
    }
}
