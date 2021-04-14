<?php

//привязка бота телеграм к этому обработчику
//https://api.telegram.org/NNN:MMM/setWebhook?url=URL

require 'TelegramApi.php';
$TelegramApi = new TelegramApi();

//поток из телеграм
$content = json_decode(file_get_contents('php://input'), true);

//если мессага от юзера
if (isset($content['message'])) {
  $tgProfile = 'https://t.me/' . $content['message']['chat']['username'];
}

//мессага юзеру
$TelegramApi->send_message('sendMessage', array(
  'chat_id' => $content['message']['chat']['id'],
  'text' => 'Профиль юзера ' . $tgProfile . ', номер чата ' . $content['message']['chat']['id'] . ' задал вопрос ' . $content['message']['text'],
));

//вбита команда типа /start
if (isset($content['message'])) {
  handle_command([
    'chat_id' => $content['message']['chat']['id'],
    'first_name' => $content['message']['chat']['first_name'],
    'last_name' => $content['message']['chat']['last_name'],
    'username' => $content['message']['chat']['username'],
    'command' => $content['message']['text'],
  ]);
}

//нажата кнопка
if (isset($content['callback_query'])) {
  handle_button([
    'chat_id' => $content['callback_query']['message']['chat']['id'],
    'first_name' => $content['callback_query']['message']['chat']['first_name'],
    'last_name' => $content['callback_query']['message']['chat']['last_name'],
    'username' => $content['callback_query']['message']['chat']['username'],
    'button' => $content['callback_query']['data'],
  ]);
}

//обработка команд
function handle_command($options) {

  switch ($options['command']) {

    ///start
    case '/start':
      $TelegramApi->send_message('sendMessage', array(
        'chat_id' => $options['chat_id'],
        'text' => 'Привяжите телеграм к магазину. Залогиньтесь и пройдите по ссылке ' . "URL" . $options['chat_id'],
      ));
      break;

    ///menu
    case '/menu':
      $TelegramApi->send_message('sendMessage', array(
        'chat_id' => $options['chat_id'],
        'text' => 'Меню команд',
        'reply_markup' => telegram_reply_markup(),
      ));
      break;
  }

}

//обработка кнопок
function handle_button($options) {

  switch ($options['button']) {

    case 'button1':
      $TelegramApi->send_message('sendMessage', array(
        'chat_id' => $options['chat_id'],
        'text' => 'text1',
        'reply_markup' => telegram_reply_markup(),
      ));
      break;

    case 'button2':
      $TelegramApi->send_message('sendMessage', array(
        'chat_id' => $options['chat_id'],
        'text' => 'text2',
        'reply_markup' => telegram_reply_markup(),
      ));
      break;
  }

}

//привязка телеграма. вставка номера чата телеграма в таблицу юзеров
function bind($telegram_chat_id) {

  $login_user = 'XXX';

  //если залогинен
  if ($login_user && $telegram_chat_id) {

    //пишем номер чата телеграм в таблицу
    $this->db
      ->where('id', $login_user)
      ->update('users', ['telegram_chat_id' => $telegram_chat_id]
      );

    $TelegramApi->send_message('sendMessage', array(
      'chat_id' => $telegram_chat_id,
      'text' => 'Привязка телеграма к магазину произведена',
      'reply_markup' => telegram_reply_markup(),
    ));

  } else {

    $TelegramApi->send_message('sendMessage', array(
      'chat_id' => $telegram_chat_id,
      'text' => 'Привязка телеграма к магазину произведена. Залогиньтесь и нажмите /start',
    ));

  }

}

//кнопки
function telegram_reply_markup() {
  return json_encode([
    'inline_keyboard' => [
      [
        ['text' => 'button1', 'callback_data' => 'button1'],
        ['text' => 'button2', 'callback_data' => 'button2']
      ]
    ]
  ]);

}