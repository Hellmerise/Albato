<?php

declare(strict_types=1);

namespace Tests\Support\Config;


enum TimeoutEnum: int
{
    case PAGE_LOAD = 10;
    case ELEMENT_VISIBLE = 5;
}