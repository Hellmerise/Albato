<?php

declare(strict_types=1);

namespace Tests\Support\Exception;


use Codeception\Exception\ModuleException;

abstract class AbstractException extends ModuleException
{
    protected string $rawMessage;
    
    protected function __construct(string $class, string $message)
    {
        $this->rawMessage = $message;
        
        $method = $this->findCallingMethod($class);
        
        parent::__construct($method ? "$class::$method" : $class, $message);
    }
    
    private function findCallingMethod(string $class): ?string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        
        foreach ($backtrace as $frame) {
            if (isset($frame['class']) && $frame['class'] === $class) {
                return $frame['function'] ?? null;
            }
        }
        
        return null;
    }
    
    final public function getMessageError(): string
    {
        return $this->rawMessage;
    }
}