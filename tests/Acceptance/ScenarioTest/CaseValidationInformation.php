<?php

declare(strict_types=1);


namespace Tests\Acceptance\ScenarioTest;


use Tests\Acceptance\ScenarioTest\Traits\LoginStepsTrait;
use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\TestCasesEnum;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;
use Tests\Support\Page\Acceptance\InformationPage;
use Tests\Support\Step\Acceptance\InformationSteps;
use Tests\Support\Step\Acceptance\LoginSteps;


abstract class CaseValidationInformation
{
    use LoginStepsTrait;
    
    private const string KEY_FIRSTNAME   = TestCasesEnum::KEY_FIRSTNAME;
    private const string KEY_LASTNAME    = TestCasesEnum::KEY_LASTNAME;
    private const string KEY_POSTAL_CODE = TestCasesEnum::KEY_POSTAL_CODE;
    
    private InformationPage  $informationPage;
    private InformationSteps $informationSteps;
    
    final public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->informationPage = new InformationPage($I);
        
        $this->loginSteps = new LoginSteps($scenario, $I);
        $this->informationSteps = new InformationSteps($scenario, $I);
        
        $this->scenario = $scenario;
        $this->acceptanceTester = $I;
    }
    
    /**
     * Тестирует валидацию обязательных полей формы информации о клиенте.
     *
     * Выполняет последовательные проверки валидации для случаев,
     * когда отсутствуют обязательные поля: имя, фамилия и почтовый индекс.
     * Для каждого случая вызывает отдельный метод, который формирует набор тестов и проверяет сообщения об ошибках.
     *
     * @return void
     */
    final protected function testValidationOfInformationFields(): void
    {
        $this->loginSteps->loginAsStandardUser();
        
        $this->testValidationWithoutFirstName();
        $this->testValidationWithoutLastName();
        $this->testValidationWithoutPostalCode();
    }
    
    /**
     * Заполняет форму информации о клиенте значениями из массива.
     *
     * Метод открывает страницу с формой и передает значения полей в обработчик одного тестового кейса.
     *
     * @param array $valueOfFields Массив с ключами:
     *
     * - `firstname` - Имя клиента;
     * - `lastname` - Фамилия клиента;
     * - `postal_code` - Почтовый индекс клиента;
     *
     * @return void
     */
    final protected function fillInformationFields(array $valueOfFields): void
    {
        $this->processTestCase($valueOfFields);
    }
    
    /**
     * Тестирует валидацию формы при отсутствии имени.
     *
     * Формирует набор тестовых кейсов с различными значениями, где поле имени отсутствует или пустое.
     * Для каждого кейса проверяет, что появляется ожидаемое сообщение об ошибке.
     *
     * @return void
     */
    private function testValidationWithoutFirstName(): void
    {
        $testCases = [
            [
                self::KEY_FIRSTNAME   => '',
                self::KEY_LASTNAME    => '',
                self::KEY_POSTAL_CODE => '',
            ],
            [
                self::KEY_FIRSTNAME   => '',
                self::KEY_LASTNAME    => 'Базарнов',
                self::KEY_POSTAL_CODE => '',
            ],
            [
                self::KEY_FIRSTNAME   => '',
                self::KEY_LASTNAME    => '',
                self::KEY_POSTAL_CODE => '123456',
            ],
            [
                self::KEY_FIRSTNAME   => '',
                self::KEY_LASTNAME    => 'Базарнов',
                self::KEY_POSTAL_CODE => '123456',
            ],
        ];
        
        $this->processTestCases(
            $testCases,
            true,
            $this->informationPage->getMessageRequiredFirstname()
        );
    }
    
    /**
     * Тестирует валидацию формы при отсутствии фамилии.
     *
     * Аналогично testValidationWithoutFirstName, но для поля фамилии.
     *
     * @return void
     */
    private function testValidationWithoutLastName(): void
    {
        $testCases = [
            [
                self::KEY_FIRSTNAME   => 'Дмитрий',
                self::KEY_LASTNAME    => '',
                self::KEY_POSTAL_CODE => '',
            ],
            [
                self::KEY_FIRSTNAME   => 'Дмитрий',
                self::KEY_LASTNAME    => '',
                self::KEY_POSTAL_CODE => '123456',
            ],
        ];
        
        $this->processTestCases(
            $testCases,
            true,
            $this->informationPage->getMessageRequiredLastname()
        );
    }
    
    /**
     * Тестирует валидацию формы при отсутствии почтового индекса.
     *
     * Аналогично предыдущим методам, но для поля postal_code.
     *
     * @return void
     */
    private function testValidationWithoutPostalCode(): void
    {
        $testCases = [
            [
                self::KEY_FIRSTNAME   => 'Дмитрий',
                self::KEY_LASTNAME    => 'Базарнов',
                self::KEY_POSTAL_CODE => '',
            ],
        ];
        
        $this->processTestCases(
            $testCases,
            true,
            $this->informationPage->getMessageRequiredPostalCode()
        );
    }
    
    /**
     * Обрабатывает несколько тестовых кейсов.
     *
     * Для каждого тестового набора данных вызывает метод обработки одного кейса.
     * Позволяет централизованно управлять логикой обработки и проверок.
     *
     * @param array  $testCases                 Массив тестовых кейсов.
     * @param bool   $isValidating              Флаг, указывающий, что необходимо проверить именно валидацию (`true`) или просто заполнить поля (`false`).
     * @param string $expectedValidationMessage Ожидаемое сообщение ошибки валидации.
     *
     * @return void
     */
    private function processTestCases(array $testCases, bool $isValidating = false, string $expectedValidationMessage = ''): void
    {
        foreach ($testCases as $testCase) {
            $this->processTestCase($testCase, $isValidating, $expectedValidationMessage);
        }
    }
    
    /**
     * Обрабатывает один тестовый кейс, извлекая необходимые поля и вызывая метод валидации или заполнения.
     *
     * Из массива тестового кейса извлекаются значения для полей firstname, lastname и postal_code.
     * Затем вызывается метод validateInformationFields с этими значениями и дополнительными параметрами,
     * отвечающими за режим работы (валидация или заполнение) и ожидаемое сообщение об ошибке.
     *
     * @param array  $testCase                  Ассоциативный массив с ключами:
     *
     * - `firstname` — имя пользователя;
     * - `lastname` — фамилия пользователя;
     * - `postal_code` — почтовый индекс.
     *
     * Значения могут отсутствовать, тогда передаётся null.
     *
     * @param bool   $isValidating              Флаг, указывающий, что необходимо выполнить проверку валидации полей.
     * @param string $expectedValidationMessage Ожидаемое сообщение об ошибке при валидации (если $isValidating = true).
     *
     * @return void
     */
    private function processTestCase(array $testCase, bool $isValidating = false, string $expectedValidationMessage = ''): void
    {
        $this->validateInformationFields(
            $testCase[self::KEY_FIRSTNAME] ?? null,
            $testCase[self::KEY_LASTNAME] ?? null,
            $testCase[self::KEY_POSTAL_CODE] ?? null,
            $isValidating,
            $expectedValidationMessage
        );
    }
    
    /**
     * Выполняет либо заполнение, либо проверку валидации полей формы.
     *
     * Если $isValidating = true, пытается заполнить форму и нажать кнопку продолжения,
     * ожидая, что при ошибках будет выброшено исключение с сообщением ошибки,
     * которое сравнивается с $expectedValidationMessage.
     * Если $isValidating = false, просто заполняет поля без проверки ошибок.
     *
     * @param string|null $firstname                 Значение поля "Имя".
     * @param string|null $lastname                  Значение поля "Фамилия".
     * @param string|null $postalCode                Значение поля "Почтовый индекс".
     * @param bool        $isValidating              Флаг, указывающий, что нужно проверить валидацию.
     * @param string      $expectedValidationMessage Ожидаемое сообщение об ошибке валидации.
     *
     * @return void
     */
    private function validateInformationFields(?string $firstname, ?string $lastname, ?string $postalCode, bool $isValidating, string $expectedValidationMessage): void
    {
        $this->informationPage->goToPage();
        
        if ($isValidating) {
            try {
                $this->informationSteps->processFillingInformationFields(
                    $firstname,
                    $lastname,
                    $postalCode
                );
                $this->informationPage->clickButtonContinue();
            } catch (AssertionEmptyFailed $assertionEmptyFailed) {
                // Обрабатываем случай, когда при переходе на страницу для заполнения информации о клиенте
                // поля для ввода имени, фамилии и почтового индекса заполнены предустановленным значением.
                // Ожидается, что по умолчанию эти поля будут пустыми, а если нет, то завершаем тест неудачей.
                $this->acceptanceTester->fail($assertionEmptyFailed->getMessage());
            } catch (InvalidDataForm $invalidDataForm) {
                // Обрабатываем случай, когда после нажатия кнопки "Continue"
                // происходит валидация заполненной формы.
                // Ожидается, что ошибка отображаемая в UI будет соответствовать ожиданиям.
                $this->acceptanceTester->assertEquals(
                    $expectedValidationMessage,
                    $invalidDataForm->getMessageError(),
                    'Тексты ошибок не совпадают'
                );
            }
        } else {
            // Просто заполняем поля без проверки ошибок
            $this->informationSteps->fillFieldsInformation(
                $firstname,
                $lastname,
                $postalCode
            );
        }
    }
}