# Структура базы данных для сайта с опросами

## Таблица category. Категории объектов

Рестораны, Клубы, Автосервисы

## Таблица object. Объекты

Конкретные объекты с названием, адресом, телефоном, координатами на карте

## Таблица poll. Опросы на объекты

На каждый объект создаются опросы. Каждый опрос имеет название

## Таблица type. Типы вопросов

Каждый опрос содержит разные вопросы таких типов:
- выберите один ответ из предложенных вариантов
- выберите несколько ответов из предложенных вариантов
- укажите цифру в указанном диапазоне
- напишите текстовый ответ
- выберите один из 5 готовых ответов
- выберите оценку от 1 до 5

## Таблица question. Вопросы

Содержит непосредственный текст вопросов

## Таблица option. Готовые варианты ответов

- текстовое поле
- цифровое поле min
- цифровое поле max

## Таблица answer

- готовый вариант ответа на вопрос
- пользовательское цифровое поле ответа
- пользовательское текстовое поле ответа

## Таблица review. Отзыв

Как сборный объект на опрос