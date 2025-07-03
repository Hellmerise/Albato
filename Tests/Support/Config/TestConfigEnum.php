<?php

declare(strict_types=1);

namespace Tests\Support\Config;


enum TestConfigEnum
{
    /**
     * @var float Налог в %
     */
    public const float TAX_RATE = 8.0;
    
    /**
     * @var int Задержка для ожидания загрузки страницы в секундах
     */
    public const int WAIT_PAGE_LOAD = 1;
    
    /**
     * @var int Задержка для ожидания видимости элемента в секундах
     */
    public const int WAIT_ELEMENT_VISIBLE = 1;
}
