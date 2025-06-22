<?php

declare(strict_types=1);

namespace Tests\Support\Config;


enum InventorySortingEnum: string
{
    case AZ = 'az';
    case ZA = 'za';
    case LOHI = 'lohi';
    case HILO = 'hilo';
    
    public const string COLUMN    = 'column';
    public const string ASCENDING = 'ascending';
    public const string MESSAGE   = 'message';
    
    final public function getSortOptions(): array
    {
        return match ($this) {
            self::AZ => [
                self::COLUMN    => InventoryColumnEnum::NAME->value,
                self::ASCENDING => true,
                self::MESSAGE   => 'Товары не отсортированы по названию A-Z',
            ],
            self::ZA => [
                self::COLUMN    => InventoryColumnEnum::NAME->value,
                self::ASCENDING => false,
                self::MESSAGE   => 'Товары не отсортированы по названию Z-A',
            ],
            self::LOHI => [
                self::COLUMN    => InventoryColumnEnum::PRICE->value,
                self::ASCENDING => true,
                self::MESSAGE   => 'Товары не отсортированы по возрастанию цены',
            ],
            self::HILO => [
                self::COLUMN    => InventoryColumnEnum::PRICE->value,
                self::ASCENDING => false,
                self::MESSAGE   => 'Товары не отсортированы по убыванию цены',
            ]
        };
    }
}
