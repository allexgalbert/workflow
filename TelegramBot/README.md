# Бот VK и Telegram для перевода диалогов

- Первая часть это бот VK который можно добавить в любой чат
- Вторая часть это переводчик на базе Google API Translate для перевода сообщений
- Третья часть отправляет переведенные сообщения в Telegram

## Бот вконтакте

- Создать сообщество https://vk.com/clubXXX
- Управление -> работа с api -> создать ключ XXX
- Управление -> работа с api -> callback api: адрес сервера http://XX.XX.XX.XX/index.php, секретный ключ XXX

## Бот телеграм

- Зайти в бота @BotFather
- Создать бота командой /newbot: задать имя "Name", ник "nameBot"
- Изменить бота командой /mybots: задать Description, About, Botpic
- Урл бота https://t.me/nameBot
- Получить токен XXX:XXX
- Привязать или отвязать бота от файла-обработчика
    - https://api.telegram.org/botTOKEN/setWebhook?url=https://domain.com/file.php
    - https://api.telegram.org/botTOKEN/deleteWebhook?url=https://domain.com/file.php

- Добавить в канал, бота как администратора
- Постить в канал по имени канала @namechannel

### Структура сообщения

```json
{
  "ok": true,
  "result": [
    {
      "update_id": 123,
      "message": {
        "message_id": 1,
        "from": {
          "id": 123,
          "is_bot": false,
          "first_name": "first_name",
          "language_code": "ru"
        },
        "chat": {
          "id": 123,
          "first_name": "first_name",
          "type": "private"
        },
        "date": 1572965904,
        "text": "текст"
      }
    }
  ]
}
```

## Привязка бота к сайту

- Привязка бота к обработчику
- Обработка команд типа /start, /menu
- Обработка кнопок
- Вставка номера чата телеграм, в таблицу юзеров

## Демо

![Бот VK и Telegram для перевода диалогов](https://raw.githubusercontent.com/allexgalbert/workflow/main/TelegramBot/1.png "Бот VK и Telegram для перевода диалогов")