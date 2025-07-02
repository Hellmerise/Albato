<?php

declare(strict_types=1);

namespace Tests\Support\Helper;


use Codeception\Exception\TestRuntimeException;
use Codeception\Module;

class AssertHelper extends Module
{
    /**
     * Утверждает, что сумма товаров равна ожидаемой.
     *
     * @param float  $expected Ожидаемая сумма.
     * @param array  $items    Список товаров.
     * @param string $key      Ключ, который хранит значение стоимости товара.
     *                         Предполагается, что сумма представлена числом.
     *
     * @return void
     */
    final public function assertSumEqual(float $expected, array $items, string $key): void
    {
        $actual = $this->sumByKey($items, $key);
        
        $this->checkExistKey($key, $items);
        
        $message = sprintf("Ожидалась сумма '%f', но в результате подсчета сумма равна '%f'", $expected, $actual);
        
        $this->assertEquals($expected, $actual, $message);
    }
    
    /**
     * Утверждает, что значения ключей уникальны в массиве и не повторяются.
     *
     * @param array  $array   Список товаров.
     * @param string ...$keys Список ключей, для которых утверждается уникальность значений.
     *
     * @return void
     */
    final public function assertUniqueValue(array $array, string ...$keys): void
    {
        $uniqueValues = [];
        
        foreach ($keys as $key) {
            $this->checkExistKey($key, $array);
            $uniqueValues[$key] = [];
        }
        
        foreach ($array as $subArray) {
            foreach ($keys as $key) {
                $value = $subArray[$key];
                
                if (isset($uniqueValues[$key][$value])) {
                    $this->fail(sprintf("Значение '%s' в ключе '%s' не является уникальным", $uniqueValues[$key][$value], $key));
                }
                $uniqueValues[$key][$value] = $subArray[$key];
            }
        }
    }
    
    
    /**
     * Считает сумму значений по ключу $key в массиве $items.
     *
     * @param array  $array
     * @param string $key
     *
     * @return float
     */
    private function sumByKey(array $array, string $key): float
    {
        $sum = 0.0;
        $firstElement = reset($array);
        
        if (is_array($firstElement)) {
            foreach ($array as $index => $subArray) {
                $sum += $this->sumBy($subArray, $key, $index);
            }
        } else {
            $sum += $this->sumBy($array, $key);
        }
        
        return $sum;
    }
    
    private function sumBy(array $array, string $key, int|string|null $index = null): float
    {
        if (empty($array)) {
            throw new TestRuntimeException("Массив пустой, невозможно посчитать сумму товаров по ключу '$key'");
        }
        
        if (isset($array[$key]) && is_numeric($array[$key])) {
            return (float)$array[$key];
        }
        
        $message = is_null($index)
            ? "В массиве отсутствует числовой ключ '$key'"
            : "В элементе с индексом $index отсутствует числовой ключ '$key'";
        
        throw new TestRuntimeException($message);
    }
    
    private function checkExistKey(string $key, array $array): void
    {
        if (empty($array)) {
            throw new TestRuntimeException("Массив пустой, невозможно проверить наличие ключа '$key'");
        }
        
        $firstElement = reset($array);
        
        if (is_array($firstElement)) {
            foreach ($array as $index => $subArray) {
                $this->assertArrayHasKey($key, $subArray,"В элементе с индексом '$index' есть ключи [" . implode(', ', array_keys($subArray)) . "], но отсутствует ключ '$key'");
            }
        } else {
            $this->assertArrayHasKey($key, $array, "В массиве есть ключи [" . implode(', ', array_keys($array)) . "], но отсутствует ключ '$key'");
        }
    }
}
