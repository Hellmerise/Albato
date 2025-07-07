# Тестирование SauceDemo

Проект автоматизированного тестирования для веб-приложения [SauceDemo](https://www.saucedemo.com).

## Стек технологий

- PHP 8.4
- Codeception
- Selenium WebDriver
- Docker

## Предварительные требования

### 1. Установка PhpStorm

1. Скачайте PhpStorm с [официального сайта](https://www.jetbrains.com/phpstorm/download/)
2. Запустите установщик и следуйте инструкциям

### 2. Установка Docker Desktop

1. Скачайте Docker Desktop для вашей ОС с [официального сайта](https://www.docker.com/products/docker-desktop/)
2. Установите приложение, следуя инструкциям установщика
3. После установки запустите Docker Desktop

## Настройка проекта

1. Cклонируйте репозиторий проекта:
   ```
   git clone https://github.com/Hellmerise/Albato.git
   ```
2. Откройте проект в PhpStorm:
   - Запустите PhpStorm
   - Выберите "Open" и укажите путь к склонированному репозиторию
3. Скопируйте файл `.env.example` в корневую директорию проекта и переименуйте его в `.env`:
   - В PhpStorm щелкните правой кнопкой мыши на файле `.env.example`
   - Выберите "Copy" и затем "Paste"
   - Переименуйте скопированный файл в `.env`
4. Добавьте параметр GITHUB_ACCESS_TOKEN, отправленный вам в письме:
   - Откройте файл `.env` в PhpStorm
   - Добавьте строку:
     ```
     GITHUB_ACCESS_TOKEN=ваш_токен
     ```
5. Запустите Docker контейнеры:
   - Откройте терминал в PhpStorm
   - Выполните команду:
     ```
     docker-compose up -d
     ```
6. Убедитесь, что контейнеры запущены:
   - В терминале выполните команду:
     ```
     docker ps
     ```
     Вы должны увидеть запущенные контейнеры `selenium-for-albato` и `chrome-for-albato`
7. Откройте командную строку в докер-контейнера:
   - Выполните:
     ```
     docker exec -it selenium-for-albato bash
     ```
8. Установите зависимости проекта:
   - Внутри контейнера выполните команду:
     ```
     composer install
     ```

## Структура проекта

```
Tests/
├── Acceptance/              # Приемочные тесты
│   ├── NoAuth/                 # Тесты без авторизации  
│   ├── Users/                  # Тесты для разных типов пользователей
│   └── ScenarioTest/           # Базовые тестовые сценарии
├── Support/                 # Вспомогательные классы
│   ├── Config/                 # Конфигурация
│   ├── Data/                   # Тестовые данные
│   ├── Exception/              # Исключения
│   ├── Helper/                 # Хелперы
│   ├── Page/                   # Page Objects
│   └── Step/                   # Step Objects
```

### Основные директории

- `Tests/` - корневая директория с тестами
    - `Acceptance/` - приемочные тесты
    - `Support/` - вспомогательные классы и конфигурация

### Ключевые компоненты

#### Page Objects
- `LoginPage` - страница авторизации
- `InventoryPage` - страница со списком товаров
- `CartPage` - корзина
- `InformationPage` - страница оформления заказа
- `OverviewPage` - страница подтверждения заказа
- `CompletePage` - страница успешного оформления заказа

#### Steps
- `LoginSteps` - шаги для авторизации
- `InventorySteps` - шаги для работы со списком товаров
- `CartSteps` - шаги для работы с корзиной
- `InformationSteps` - шаги для заполнения информации
- `OverviewSteps` - шаги для подтверждения заказа
- `CompleteSteps` - шаги для завершения заказа

#### Test Cases
Основные тестовые сценарии:
- Авторизация с разными пользователями
- Сортировка товаров
- Добавление товаров в корзину
- Оформление заказа
- Валидация форм

#### Конфигурация
- `TestCasesEnum` - константы для тестовых данных
- `TestConfigEnum` - конфигурационные параметры
- `UsersEnum` - данные тестовых пользователей

#### Helpers
- `AssertHelper` - методы для проверок
- `GrabHelper` - методы для извлечения данных

## Основные тесты

Тесты нужно запускать из командной строки докер-контейнера.

1. Сортировка товаров по возрастанию цены
   - Для запуска тестов используйте команду:
     ```bash
     php vendor/bin/codecept run -g first
     ```
   - Группа тестов включает в себя авторизацию под каждым пользователем из примера на сайте
   - Пример класса Cest находится в `Tests/Acceptance/Users/***/SortingProductsInventory***Cest.php`
   - Общий тестовый сценарий, который переиспользуется в классах Cest описан в `Tests/Acceptance/ScenarioTest/CaseSortingProducts.php`
   - Тестовые данные для теста описаны в `Tests/Support/Data/Acceptance/testcase_for_sorting_inventory.php`
     - Сейчас там сортировка всех видов, но так как по заданию нужна "по возрастанию цены", то можно оставить только вариант:
     ```php
     'modeSort' => [
            TestCasesEnum::VALUE_SORT_PRICE_ASC,
     ]
     ```
2. Переход к оформлению заказа без авторизации
   - Для запуска теста используйте команду:
     ```bash
     php vendor/bin/codecept run -g second
     ```
   - Тест проверяет доступ к страницам без авторизации и наличие ошибок при попытке оформления заказа
   - Пример класса Cest находится в `Tests/Acceptance/NoAuth/AccessWithoutAuthorizationCest.php`
   - Общий тестовый сценарий, который переиспользуется в классах Cest описан в `Tests/Acceptance/ScenarioTest/CaseAccessWithoutAuthorization.php`
   - Тестовые данные для теста описаны в общем сценарии:
   ```php
        private const array  PAGES = [
        '/inventory.html',
        '/cart.html',
        '/checkout-step-one.html',
        '/checkout-step-two.html',
        '/checkout-complete.html',
        '/inventory-item.html',
        '/inventory-item.html?id=1',
    ];
   ```
   - Так как по условию задачи нужно проверить переход к оформлению заказа без авторизации, то можно оставить только следующие страницы:
   ```php
   private const array  PAGES = [
        '/checkout-step-one.html',
        '/checkout-step-two.html',
   ```
3. Попытка оформления заказа без заполненного поля “Zip/Postal Code
4. Полное оформление заказа с добавлением товара в корзину и оплатой
5. Оформление заказа с пустой корзиной товаров
   - Пункты 3,4,5 объеденины в один тестовый сценарий, который проверяет процесс покупки товаров
   - Для запуска теста используйте команду:
     ```bash
     php vendor/bin/codecept run -g third
     ```
   - Пример класса Cest находится в `Tests/Acceptance/Users/Standart/ProcessPurchaseByStandardUserCest.php`
   - Общий тестовый сценарий, который переиспользуется в классах Cest описан в `Tests/Acceptance/ScenarioTest/CasePurchaseProducts.php`
   - Тестовые данные для теста описаны в `Tests/Support/Data/Acceptance/testcase_for_purchase_products.php`
   - Тестовые данные для теста включают в себя следующие ключи:
     - `TestCasesEnum::KEY_COUNTS_PRODUCTS` - количество товаров, которые нужно добавить в корзину
     - `TestCasesEnum::KEY_FIRSTNAME` - имя покупателя
     - `TestCasesEnum::KEY_LASTNAME` - фамилия покупателя
     - `TestCasesEnum::KEY_POSTAL_CODE` - почтовый индекс покупателя
   - Комбинация этих ключей позволяет проверить различные сценарии оформления заказа, например:
     - Пункту 3 соответствует тестовый случай:
       ```php
       [
          TestCasesEnum::KEY_FIRSTNAME => 'Дмитрий',
          TestCasesEnum::KEY_LASTNAME  => 'Базарнов',
       ]
       ```
       В этом случае не добавляются товары, так как пропущено количество товаров для добавления в корзину, и не заполняется поле с почтовым индексом, так как оно отсутствует в тестовых данных.
     - Пункту 4 соответствует тестовый случай:
       ```php
       [
          TestCasesEnum::KEY_COUNTS_PRODUCTS => 6,
          TestCasesEnum::KEY_FIRSTNAME       => 'Дмитрий',
          TestCasesEnum::KEY_LASTNAME        => 'Базарнов',
          TestCasesEnum::KEY_POSTAL_CODE     => '123456',
       ]
       ```
       В этом случае добавляются 6 случайных товаров в корзину и заполняются все поля с информацией о покупателе.
     - Пункту 5 соответствует тестовый случай:
       ```php
       [
          TestCasesEnum::KEY_COUNTS_PRODUCTS => 0,
          TestCasesEnum::KEY_FIRSTNAME       => 'Дмитрий',
          TestCasesEnum::KEY_LASTNAME        => 'Базарнов',
          TestCasesEnum::KEY_POSTAL_CODE     => '123456',
       ]
       ```
       В этом случае не добавляются товары в корзину, потому что явно указано количество товаров равное 0, но заполняются все поля с информацией о покупателе.

## Дополнительные тесты

В проекте также реализованы дополнительные тесты для проверки различных сценариев и ошибок.

1. Валидация полей для ввода логина и пароля.
    - Для запуска теста используйте команду:
     ```bash
     php vendor/bin/codecept run -g login
     ```
2. Валидация полей для ввода информации о покупателе.
    - Для запуска теста используйте команду:
     ```bash
     php vendor/bin/codecept run -g info
     ```

## Запуск тестов

Находясь в командной строке контейнера, вы можете запускать тесты следующими командами:

- Запуск всех тестов:
```bash
php vendor/bin/codecept run
```

- Запуск определённой группы тестов, например `first`:
```bash
php vendor/bin/codecept run -g first
```

- Запуск тестов с подробным выводом:
```bash
php vendor/bin/codecept run -vvv
```

- Запуск группы тестов с подробным выводом:
```bash
php vendor/bin/codecept run -g first -vvv
```

- Запуск всех тестов с исключением определённой группы, например `first`:
```bash
php vendor/bin/codecept run -x first
```

