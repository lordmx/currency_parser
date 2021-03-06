# Тестовый парсер валют

## Установка

* Клонируем проект:

```
$ git clone git@github.com:lordmx/currency_parser.git
```

* Переходим в директорию проекта:

```
$ cd currency_parser
```

* Качаем бинарник composer:

```
$ curl -sS https://getcomposer.org/installer | php
```

* Получаем зависимые пакеты:

```
$ php composer.phar install
```

* Используем mysql-клиент для установки соединения с СУБД mysql:

```
$ mysql -uroot
```

* Создаем новую БД:

```
CREATE DATABASE currency_parser;
```

* Даем пользователю root права на нее:

```
GRANT ALL ON currency_parser.* TO 'root'@'%';
```

* Меняем текущую БД на новую:

```
use currency_parser
```

* И создаем в ней новую таблицу для валют:

```
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `last_updated_at` datetime DEFAULT NULL,
  `rate` decimal(12,8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `iso` (`iso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```

* Добавляем тестовые данные:

```
INSERT INTO currencies(iso, title) VALUES
    ('EUR', 'EURO'),
    ('USD', 'USA Dollar'),
    ('UAH', 'Украинская гривна'),
    ('BYR', 'Белорусский рубль');
```

* Переходим в директорию проекта и запускаем cli-скрипт получения курсов для добавленных валют:

```
$ php currency_parser/public/index.php Schedule
```

* Создаем конфигурацию для хоста (путь до index.php - currency_parser/currency_parser/public/)

* Вызов скрипта парсинга курсов следует добавить в crontab (с интервалом раз в 1 час)

* Для php 5.5 и выше в php.ini, возможно, придется установить значение переменно **always_populate_raw_post_data** в -1

## Примеры запросов к API:

* Получить список из первых 10 валют:

```
$ curl -X GET $API_HOST/api/v1/currencies?limit=10&offset=0
```

* Найти валюту по ISO-коду USD:

```
$ curl -X GET $API_HOST/api/v1/currencies?iso=USD
```

* Получить валюту по ID:

```
$ curl -X GET $API_HOST/api/v1/currencies/1
```

* Добавить новую валюту:

```
$ curl -X POST -H "Content-type: application/json" $API_HOST/api/v1/currencies -d '{"iso": "GBP", "title": "British pound"}'
```

* Изменить название валюту (курс пересчитывает при любом редактировании)

```
$ curl -X PUT -H "Content-type: application/json" $API_HOST/api/v1/currencies/1 -d '{"title": "TEST"}'
```

* Удалить валюту:

```
$ curl -X DELETE $API_HOST/api/v1/currencies/1
```

## Примечания по коду:

* Используемый стандарт кодирования: Zend
* API сделано в виде отдельного модуля (api)
* Бизнес логика вынесена в слой application services и моделей
* API взаимодействует с сервисами через DTO
* Реализован маппинг request->dto, dto->model, model->dto, dto->response через аннотации
* Парсеры курсов можно подменять, для этого нужно реализовать класс, использующий интерфейс Api_CurrencyParser_Interface
* Умолчательный парсер используется данные с Yahoo.finance
* Кэшировать результаты работы API-action рекомендуется через HTTP-заголовки кэширования (на стороне того же nginx),
для этого реализованы методы **setCacheExpires** и **setCacheControl** у базового API-контроллера