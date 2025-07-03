<?php

declare(strict_types=1);

namespace Tests\Support\Exception;


final class InvalidDataForm extends AbstractException
{
    final public function __construct(string $class, string $message)
    {
        parent::__construct($class, $message);
    }
}