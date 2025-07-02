<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;
use Tests\Support\Page\Abstract\AbstractMenuPage;

/**
 * Страница оформления заказа с информацией о пользователе.
 *
 * Класс представляет страницу ввода информации о пользователе при оформлении заказа.
 * Содержит локаторы для полей ввода личных данных, xpath-селекторы элементов и методы
 * для взаимодействия с формой оформления заказа.
 */
final class InformationPage extends AbstractMenuPage
{
    /**
     * @var string URL страницы.
     */
    private const string URL = '/checkout-step-one.html';
    
    /**
     * @var string Заголовок страницы.
     */
    private const string   TITLE = 'Checkout: Your Information';
    
    /**
     * @var string Xpath поля для ввода имени.
     */
    private const string FIELD_FIRSTNAME_XPATH = "//input[@data-test = 'firstName']";
    
    /**
     * @var string Xpath поля для ввода фамилии.
     */
    private const string FIELD_LASTNAME_XPATH = "//input[@data-test = 'lastName']";
    
    /**
     * @var string Xpath поля для ввода почтового индекса.
     */
    private const string FIELD_POSTAL_CODE_XPATH = "//input[@data-test = 'postalCode']";
    
    /**
     * @var string Xpath кнопки "Продолжить".
     */
    private const string BUTTON_CONTINUE_XPATH = "//input[@data-test = 'continue']";
    
    /**
     * @var string Xpath для кнопки "Отмена".
     */
    private const string BUTTON_CANCEL_XPATH = "//button[@data-test = 'cancel']";
    
    /**
     * @var string Ошибка при пустом поле "Firstname", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_REQUIRED_FIRSTNAME = 'Error: First Name is required';
    
    /**
     * @var string Ошибка при пустом поле "Lastname", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_REQUIRED_LASTNAME = 'Error: Last Name is required';
    
    /**
     * @var string Ошибка при пустом поле "PostalCode", так как оно является обязательным.
     */
    private const string MESSAGE_ERROR_REQUIRED_POSTAL_CODE = 'Error: Postal Code is required';
    
    /**
     * @inheritDoc
     */
    protected string $url {
        get {
            return self::URL;
        }
    }
    
    /**
     * @inheritDoc
     */
    protected string $title {
        get {
            return self::TITLE;
        }
    }
    
    /**
     * @inheritDoc
     */
    final protected array $wait_elements {
        get {
            return [
                self::FIELD_FIRSTNAME_XPATH,
                self::FIELD_LASTNAME_XPATH,
                self::FIELD_POSTAL_CODE_XPATH,
                self::BUTTON_CONTINUE_XPATH,
            ];
        }
    }
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    /**
     * Заполняет поле для ввода имени.
     *
     * @param string $firstName Имя пользователя.
     *
     * @return void
     *
     * @throws AssertionEmptyFailed
     */
    final public function fillFirstname(string $firstName): void
    {
        $this->assertFieldEmpty(self::FIELD_FIRSTNAME_XPATH);
        $this->fillField(self::FIELD_FIRSTNAME_XPATH, $firstName);
    }
    
    /**
     * Заполняет поле для ввода фамилии.
     *
     * @param string $lastName Фамилия пользователя.
     *
     * @return void
     *
     * @throws AssertionEmptyFailed
     */
    final public function fillLastname(string $lastName): void
    {
        $this->assertFieldEmpty(self::FIELD_LASTNAME_XPATH);
        $this->fillField(self::FIELD_LASTNAME_XPATH, $lastName);
    }
    
    /**
     * Заполняет поле для ввода почтового индекса.
     *
     * @param string $postalCode Почтовый индекс.
     *
     * @return void
     *
     * @throws AssertionEmptyFailed
     */
    final public function fillPostalCode(string $postalCode): void
    {
        $this->assertFieldEmpty(self::FIELD_POSTAL_CODE_XPATH);
        $this->fillField(self::FIELD_POSTAL_CODE_XPATH, $postalCode);
    }
    
    /**
     * Нажимает на кнопку продолжения оформления покупки.
     *
     * @return void
     *
     * @throws InvalidDataForm Если валидация формы данных покупателя не прошла успешно.
     */
    final public function clickButtonContinue(): void
    {
        $this->clickButton(self::BUTTON_CONTINUE_XPATH);
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненном имени.
     *
     * @return string
     */
    final public function getMessageRequiredFirstname(): string
    {
        return self::MESSAGE_ERROR_REQUIRED_FIRSTNAME;
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненной фамилии.
     *
     * @return string
     */
    final public function getMessageRequiredLastname(): string
    {
        return self::MESSAGE_ERROR_REQUIRED_LASTNAME;
    }
    
    /**
     * Возвращает текст ожидаемой ошибки при незаполненном почтовом индексе.
     *
     * @return string
     */
    final public function getMessageRequiredPostalCode(): string
    {
        return self::MESSAGE_ERROR_REQUIRED_POSTAL_CODE;
    }
    
    /**
     * Нажимает на кнопку возврата к покупкам.
     *
     * @return void
     */
    final public function clickButtonCancel(): void
    {
        self::$acceptanceTester->click(self::BUTTON_CANCEL_XPATH);
    }
}
