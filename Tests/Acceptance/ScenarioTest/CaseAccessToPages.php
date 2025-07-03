<?php

declare(strict_types=1);

namespace Tests\Acceptance\ScenarioTest;


use Codeception\Exception\ElementNotFound;
use Codeception\Exception\TestRuntimeException;
use Tests\Acceptance\ScenarioTest\Traits\LoginPageTrait;


abstract class CaseAccessToPages
{
    use LoginPageTrait;
    
    /**
     * @var array Список страниц системы, которые доступны только авторизованным пользователям.
     */
    private const array  PAGES = [
        '/inventory.html',
        '/cart.html',
        '/checkout-step-one.html',
        '/checkout-step-two.html',
        '/checkout-complete.html',
        '/inventory-item.html',
        '/inventory-item.html?id=1',
    ];
    
    /**
     * @var string Шаблон сообщения об ошибке при переходе на страницу, к которой запрещен доступ без авторизации.
     */
    private const string FORMAT_MESSAGE_ERROR = "Epic sadface: You can only access '%s' when you are logged in.";
    
    /**
     * Проверяет, что при попытке доступа к указанным страницам без авторизации
     * отображается корректное сообщение об ошибке доступа.
     *
     * @param array $pages Список страниц, при переходе на которые должна быть ошибка доступа.
     *
     * Если параметр $pages пустой, проверяется доступ к страницам из константы PAGES.
     *
     * Пример входных данных:
     * ```
     * [
     *      '/inventory.html',
     *      '/cart.html',
     *      '/checkout-step-one.html',
     * ]
     * ```
     * или
     * ```
     * [
     *      '/checkout-step-one.html',
     * ]
     * ```
     *
     * @return void
     */
    final protected function testAccessToPagesWithoutAuthorization(array $pages = []): void
    {
        $testCases = empty($pages)
            ? self::PAGES
            : $pages;
        
        foreach ($testCases as $page) {
            $this->acceptanceTester->comment("Проверяю доступ к странице '$page'");
            $this->testAccessToPage($page);
        }
    }
    
    /**
     * Выполняет проверку доступа к одной странице без авторизации.
     *
     * Переходит на страницу, ожидает загрузки, затем проверяет, что отображается
     * корректное сообщение об ошибке доступа. Если сообщение не найдено,
     * выбрасывается исключение с описанием возможных причин.
     *
     * @param string $page URL страницы для проверки.
     *
     * @return void
     *
     * @throws TestRuntimeException Если сообщение об ошибке не найдено на странице.
     */
    private function testAccessToPage(string $page): void
    {
        $this->acceptanceTester->amOnPage($page);
        $this->acceptanceTester->waitForPageLoad();
        
        if (str_starts_with($page, '/inventory-item.html')) {
            $page = '/inventory-item.html';
        }
        
        try {
            $actualMessage = $this->loginPage->getMessageError();
            
            $this->acceptanceTester->assertEquals(
                sprintf(
                    self::FORMAT_MESSAGE_ERROR,
                    $page,
                ),
                $actualMessage,
                'Тексты ошибок не совпадают'
            );
        } catch (ElementNotFound) {
            throw new TestRuntimeException(
                "Не удалось извлечь текст ошибки при переходе на страницу: '$page'.\n
            Возможные причины:\n
            - страница не существует,\n
            - переход на страницу прошел успешно,\n
            - ошибка не отображается,\n
            - ошибка отображается в другом элементе."
            );
        }
    }
}