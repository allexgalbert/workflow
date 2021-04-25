# Бот VK и Telegram для перевода диалогов

- Первая часть это бот VK который можно добавить в любой чат
- Вторая часть это переводчик на базе Google API Translate для перевода сообщений
- Третья часть отправляет переведенные сообщения в Telegram

## Бот вконтакте

- Создать сообщество https://vk.com/clubXXX
- Управление -> работа с api -> создать ключ XXX
- Управление -> работа с api -> callback api: адрес сервера http://XX.XX.XX.XX/index.php, секретный ключ XXX

## Бот телеграм

- В телеге идем сюда @BotFather
- Далее команды /start, /newbot выбрать имя "MyNameBot" и урл "MyNameBot"
- Урл бота http://t.me/MyNameBot
- Получить токен XXX:XXX
- Кинуть в консоль хостинга чтобы увидеть id чатов кто писал https://api.telegram.org/botXXX:XXX/getUpdates
- Получить структуру сообщения
```json
{
   "ok":true,
   "result":[
      {
         "update_id":XXX,
         "message":{
            "message_id":1,
            "from":{
               "id":XXX,
               "is_bot":false,
               "first_name":"first_name",
               "language_code":"ru"
            },
            "chat":{
               "id":XXX,
               "first_name":"first_name",
               "type":"private"
            },
            "date":1572965904,
            "text":"текст"
         }
      },
   ]
}
```

## Привязка бота к сайту

- Привязка бота к обработчику
- Обработка команд типа /start, /menu
- Обработка кнопок
- Вставка номера чата телеграм, в таблицу юзеров