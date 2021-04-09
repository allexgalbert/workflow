# Переводчик текстов на основе Google Api Translator

Для чатов Вконтакте. С пересылкой в бота Telegram.

## Бот вконтакте
- создать сообщество https://vk.com/clubXXX
- управление -> работа с api -> создать ключ XXX
- управление -> работа с api -> callback api: адрес сервера http://XX.XX.XX.XX/index.php, секретный ключ XXX

## Бот телеграм

- в телеге идем сюда @BotFather
- далее команды /start, /newbot выбрать имя "MyNameBot" и урл "MyNameBot"
- урл бота http://t.me/MyNameBot
- получить токен XXX:XXX
- кинуть в консоль хостинга чтобы увидеть id чатов кто писал https://api.telegram.org/botXXX:XXX/getUpdates
- получить структуру сообщения
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