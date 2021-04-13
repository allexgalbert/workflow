# Работа с dating.ru

- Парсинг профилей по параметрам: пол, возраст, город
- Сбор профилей в бд
- Постинг на профиля по параметрам: пол, колво профилей за раз
- Кастомные сообщения с автоподстановками имени профиля

## Запуск сервера Selenium
```sh
"jre1.8.0_271\bin\java.exe" -Dwebdriver.chrome.driver=chromedriver.exe -jar "server.jar"
```

## Запуск парсера профилей
```sh
php parser.php
```

## Запуск постинга на профиля
```sh
php poster.php
```

## Статистика

```sql
-- отправленные
SELECT COUNT(*)
FROM `profiles`
WHERE EXISTS (SELECT * FROM `accounts_has_profiles` WHERE `accounts_has_profiles`.`profiles_id` = `profiles`.`id`)
UNION

-- неотправленные
SELECT COUNT(*)
FROM `profiles`
WHERE NOT EXISTS (SELECT * FROM `accounts_has_profiles` WHERE `accounts_has_profiles`.`profiles_id` = `profiles`.`id`)
UNION

-- все
SELECT COUNT(*) FROM `profiles`;
```