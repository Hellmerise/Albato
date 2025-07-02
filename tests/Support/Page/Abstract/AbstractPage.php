<?php

declare(strict_types=1);

namespace Tests\Support\Page\Abstract;


use PHPUnit\Framework\AssertionFailedError;
use Tests\Support\Config\TestCasesEnum;
use Tests\Support\Config\TestConfigEnum;
use Tests\Support\AcceptanceTester;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;

/**
 * Абстрактный класс, реализующий базовую функциональность для работы со страницами.
 *
 * Предоставляет методы для ожидания видимости/невидимости элементов на странице,
 * проверки наличия элементов и получения текста из элементов.
 */
abstract class AbstractPage
{
    /**
     * @var string Xpath логотипа.
     */
    private const string PAGE_LOGO_XPATH = "//div[@class = 'login_logo'] | //div[@class = 'app_logo']";
    
    /**
     * @var string Xpath элемента ошибки.
     */
    private const string MESSAGE_ERROR_XPATH = "//h3[@data-test = 'error']";
    
    /**
     * @var int Константа для установки задержки ожидания видимости элементов.
     */
    final protected const int VISIBLE_TIMEOUT = TestConfigEnum::WAIT_ELEMENT_VISIBLE;
    
    /**
     * @var AcceptanceTester Свойство для хранения Актера.
     */
    protected static AcceptanceTester $acceptanceTester;
    
    /**
     * @var string Текущая страница.
     */
    abstract protected string $url {
        get;
    }
    
    /**
     * @var string[] Список xpath для элементов, используемых для определения видимости \ невидимости страницы.
     */
    abstract protected array $wait_elements {
        get;
    }
    
    /**
     * Переходит на текущую страницу через URL.
     *
     * @return void
     */
    final public function goToPage(): void
    {
        static::$acceptanceTester->amOnPage($this->url);
        $this->waitForPageVisible();
    }
    
    /**
     * Утверждает, что фактический текст логотипа соответствует ожидаемому.
     *
     * @return void
     */
    final public function assertLogoPage(): void
    {
        $expected = TestCasesEnum::TEXT_LOGO;
        $actual = static::$acceptanceTester->grabTextFrom(self::PAGE_LOGO_XPATH);
        static::$acceptanceTester->assertEquals(
            $expected,
            $actual,
           "Текст логотипа '$actual' не соответствует ожидаемому"
        );
    }
    
    /**
     * Ожидание видимости для PageObject.
     *
     * @return void
     */
    final public function waitForPageVisible(): void
    {
        foreach ($this->wait_elements as $element) {
            static::$acceptanceTester->waitForElementVisible($element, self::VISIBLE_TIMEOUT);
        }
    }
    
    /**
     * Ожидание невидимости для PageObject.
     *
     * @return void
     */
    final public function waitForPageNotVisible(): void
    {
        foreach ($this->wait_elements as $element) {
            static::$acceptanceTester->waitForElementNotVisible($element, self::VISIBLE_TIMEOUT);
        }
    }
    
    /**
     * Получает текст сообщения об ошибке из страницы.
     *
     * @return string Текст сообщения об ошибке.
     */
    final public function getMessageError(): string
    {
        return static::$acceptanceTester->grabTextFrom(self::MESSAGE_ERROR_XPATH);
    }
    
    /**
     * Утверждает, что элемент на странице имеет пустое содержимое.
     *
     * @param string $field Css-селектор или Xpath выражение для поиска элемента.
     *
     * @return void
     *
     * @throws AssertionEmptyFailed
     */
    final protected function assertFieldEmpty(string $field): void
    {
        try {
            $value = static::$acceptanceTester->grabValueFrom($field);
            static::$acceptanceTester->assertEquals(
                0,
                strlen($value),
               "Поле $field ожидалось пустым, но содержит значение '$value'"
            );
        } catch (AssertionFailedError $assertionFailedError) {
            throw new AssertionEmptyFailed(get_called_class(), $assertionFailedError->getMessage());
        }
    }
    
    /**
     * Заполняет поле указанным значением.
     *
     * Утверждает, что поле содержит указанное значение.
     *
     * @param string $field CSS-селектор или Xpath выражение для поиска элемента на странице.
     * @param string $value Значение, которое нужно ввести в поле.
     *
     * @return void
     */
    final protected function fillField(string $field, string $value): void
    {
        static::$acceptanceTester->fillField($field, $value);
        static::$acceptanceTester->seeInField($field, $value);
    }
    
    /**
     * Выбирает элемент из выпадающего списка.
     *
     * Утверждает, что элемент содержит указанное значение.
     *
     * @param string $field CSS-селектор или Xpath выражение для поиска элемента на странице.
     * @param string $value Значение, которое нужно выбрать из списка.
     *
     * @return void
     */
    final protected function selectOption(string $field, string $value): void
    {
        static::$acceptanceTester->selectOption($field, $value);
        static::$acceptanceTester->seeOptionIsSelected($field, $value);
        static::$acceptanceTester->waitForPageLoad();
    }
    
    /**
     * Нажимает на кнопку проверки валидации формы.
     *
     * @param string $link CSS-селектор или Xpath кнопки.
     *
     * @return void
     *
     * @throws InvalidDataForm Если нажатие кнопки приводит к появлению ошибки валидации формы.
     */
    final protected function clickButton(string $link): void
    {
        static::$acceptanceTester->click($link);
        
        if ($this->isError()) {
            throw new InvalidDataForm(get_called_class(), $this->getMessageError());
        }
    }
    
    /**
     * Возвращает `true` если отображается ошибка на форме нажатия кнопки.
     *
     * @return bool
     */
    private function isError(): bool
    {
        return static::$acceptanceTester->tryToSeeElement(self::MESSAGE_ERROR_XPATH);
    }
}