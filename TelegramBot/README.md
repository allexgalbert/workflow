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
- Создать бота командой /newbot, выбрать имя "MyNameBot" и урл "MyNameBot"
- Изменить бота командой /mybots, задать Description, About, Botpic
- Урл бота https://t.me/MyNameBot
- Получить токен XXX:XXX
- Добавить в канал, бота как администратора
- Постить в канал по имени канала @namechannel
- Кинуть в консоль хостинга https://api.telegram.org/botXXX:XXX/getUpdates чтобы увидеть id чатов кто писал
- Получить структуру сообщения

```json
{
  "ok": true,
  "result": [
    {
      "update_id": XXX,
      "message": {
        "message_id": 1,
        "from": {
          "id": XXX,
          "is_bot": false,
          "first_name": "first_name",
          "language_code": "ru"
        },
        "chat": {
          "id": XXX,
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