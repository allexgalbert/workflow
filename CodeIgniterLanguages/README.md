# Реализация многоязычности на фреймворке CodeIgniter

- Определение локали и языка браузера пользователя
- Установка языка в урл /lang/ для всех ссылок
- Подгрузка файлов с сообщениями из языковых папок

## Ресурсы

- ISO коды стран https://ru.wikipedia.org/wiki/ISO_3166-1
- Телефонные коды стран https://ru.wikipedia.org/wiki/Телефонные_коды_стран
- Коды языков https://ru.wikipedia.org/wiki/Коды_языков

## Получить язык и локаль из браузера

```php
substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 3, 5);
```

## Установить локаль в хуке application/hooks/language.php

```php
setlocale(LC_ALL, $langs[$CI->uri->segments[1]][2] . '.UTF8');

//проверить
var_dump(1.55); var_dump(strftime('%A %B'));
```

## Отладка

```php
//урл языка
$this->config->item('lang');

//папка языка
$this->config->item('language');

//ссылка с автоматическим добавлением языка
mysiteurl();
mysiteurl('controller');

site_url();
site_url('controller');

base_url();
base_url('controller');

current_url();

uri_string();

$this->uri->segments;

myrequesturi();

public_url();

upload_url();
```

## Языковые файлы ошибок *_lang.php

- calendar дни месяцы
- date секунды минуты дни недели месяцы годы таймзоны
- db ошибки базы данных
- email ошибки отправки почты
- form_validation ошибки валидации форм
- ftp ошибки работы с FTP
- imglib ошибки работы с библиотекой GD
- migration ошибки работы с миграциями
- number терабайты мебагайты килобайты
- pagination надписи для кнопок пагинации
- profiler сообщения профилировщика
- unit_test юнит тестирование
- upload ошибки загрузки изображений

## Работа с языковыми файлами

```php
//переопределить сообщение из файла
$this->form_validation->set_message('rule', 'text');

//отобразить одно сообщение
form_error('field');

//загрузить файл
//filename имя языкового файла без _lang.php
//language язык (название папки), если параметра нет, то берется из конфига application/config/config.php: $config['language'];
//если 3й параметр true, то вернет массив всех строк
$this->lang->load('file', 'language', false);

//получить сообщение из файла. язык взялся по-умолчанию из конфига
$this->lang->load('form_validation');
$this->lang->line('form_validation_required');

//сбросить язык
$this->lang->is_loaded = [];
$this->lang->language = [];

//получить сообщение из файла язык установим сами
$this->lang->load('form_validation', 'english');
$this->lang->line('form_validation_required');

//изменим язык по умолчанию в конфиге на лету
$this->config->set_item('language', 'english');
```

## Варианты

![Реализация многоязычности на фреймворке CodeIgniter](https://raw.githubusercontent.com/allexgalbert/workflow/main/CodeIgniterLanguages/1.png "Реализация многоязычности на фреймворке CodeIgniter")

## Запросы

Для 1 варианта. Все части блока, по id блока и id языка (2 таблицы. 1 join)

```sql
SELECT elements.id, elements.name, translations.name
FROM elements,
     translations
WHERE elements.id = translations.elements_id
  AND elements.components_id = 2
  AND translations.langs_id = 4;
```

Для 1 варианта. Все части блока, по имени блока и iso языка (4 таблицы. 3 join)

```sql
SELECT elements.id, elements.name, translations.name
FROM langs,
     components,
     elements,
     translations
WHERE langs.id = translations.langs_id
  AND components.id = elements.components_id
  AND elements.id = translations.elements_id
  AND components.name = 'footer'
  AND langs.iso = 'fr';
```

Для 3 варианта. Все части блока, по имени блока и iso языка (3 таблицы. 2 join)

```sql
SELECT elements2.id, elements2.name, translations2.name_ru
FROM components2,
     elements2,
     translations2
WHERE components2.id = elements2.components2_id
  AND elements2.id = translations2.elements2_id
  AND components2.name = 'header';
```