# Очистка баз данных MySQL от мусора

- От плохих ссылок, спама, вредоносного кода, нецензурного контента
- Автоматическая очистка по всем таблицам и полям
- По ключевым словам и словосочетаниям

## select.php

- Массив слов для первоначального поиска класть в words1.txt
- Генерируются и выполняются SELECT-запросы
- Результаты складываются в results/бд_слово.txt, каждое слово в свой файл
- Из этих файлов собирать конкретные слова в words2.txt. Только слова, ссылку-родитель replacer.php удалит сам

## update.php

- Выбрать конкретные слова для удаления или замены из words2.txt
- Генерируются и выполняются SELECT-запросы, прогоняются через replacer.php, и затем UPDATE

## mysql.php

- Настройка сервера (хост, бд, логин, пароль)
- Настройка конфигурации (разрешенные/запрещенные таблицы/поля/типы полей)
- Настройка дебаг-режима (показывать конфигурацию таблиц/полей, или нет)

## replacer.php

- Функция get_newvalue_text() вырезает целиком тег <a> в котором нашлось слово. Если тега <a> нет, то вырезает просто
  слово
- Функция get_newvalue_text2() заменяет слово на слово. Слово замены указать в самой функции