<?php

$db = new Db;
$throttle = new Throttle;

//бот
$bot = $db->bot(1);

$telegram = new Telegram;

//поток из чата
$answer = new Answer($telegram->flow());

//юзер
$user = $answer->user();

//обёртка
$connector = new Connector($telegram, $bot, $user, $throttle);
//-------------------------------------------------

//кнопка Старт
if ($answer->isStart()) {
  $connector->sendMessage('message');
  exit;
}

//вставить юзера
$db->insertUser($bot, $user);

//проверка времени
if ($throttle->checkTime($bot, $user)) {
  $connector->sendMessage(Ads::checkTime());
  exit;
}

//обычная кнопка
if ($answer->isButton('Button')) {
  $connector->sendMessage('message');
  exit;
}


//нажали на кнопку Callback
if ($answer->isCallback('Callback')) {
  $connector->answerCallbackQuery($answer->data);
  exit;
}

//текст
if ($getText = $answer->getText()) {
  $connector->sendMessage('message');
  $connector->sendMessage(Ads::variant1());
  exit;
}

//-------------------------------------------------

class Connector {

  public function __construct($telegram, $bot, $user, $throttle) {
    $this->telegram = $telegram;
    $this->bot = $bot;
    $this->user = $user;
    $this->throttle = $throttle;
  }

  //обработать нажатие кнопки типа Callback
  public function answerCallbackQuery($data) {

    //попап
    $this->telegram->run($this->bot['token'], 'answerCallbackQuery', [
      'callback_query_id' => $data['callback_query']['id'],
      'text' => 'Press ' . $data['callback_query']['data'],
      'show_alert_' => true
    ]);

    //мессага с которой прилетели кнопки: $content['callback_query']['message']['chat']['text']
    $this->telegram->run($this->bot['token'], 'sendMessage', [
      'chat_id' => $data['callback_query']['message']['chat']['id'],
      'text' => 'Answer for press button ' . $data['callback_query']['data'],
    ]);
  }

  //отправить сообщение
  public function sendMessage($text) {
    $this->telegram->run($this->bot['token'], 'sendMessage', [
      'chat_id' => $this->user->id,
      'text' => $text,
      'disable_web_page_preview' => 1,
      //отправить клавиатуру
      'reply_markup' => buttons1()
    ]);
    $this->addTime();
  }

  //добавить N секунд
  private function addTime() {
    $this->throttle->addTime($this->bot, $this->user, 3);
  }
}

//клавиатура обычная
function buttons1() {
  return json_encode([
    'keyboard' => [
      [
        ['text' => 'ButtonSimple']
      ],
      [
        ['text' => 'ButtonSendPhone', 'request_contact' => true],
        ['text' => 'ButtonSendLocation', 'request_location' => true],
      ],
    ],

    //маленький размер
    'resize_keyboard' => true,

    //показывать клавиатуру только тому, кто вызвал
    'selective' => true,

    //скрыть клавиатуру, после нажатия на любую кнопку
    //'one_time_keyboard' => true,
  ]);
}

//клавиатура инлайн
function buttons2() {
  return json_encode([
    'inline_keyboard' => [
      [
        //кнопка оплаты
        //['text' => 'Pay', 'pay' => true],

        //авторизация через телеграм
        //['text' => 'Login_url', 'login_url' => ['url' => 'https://google.com']],
        //['text' => 'Login_url', 'login_url' => 'https://google.com'],
      ],
      [
        ['text' => 'Callback', 'callback_data' => 'Callback'],
        ['text' => 'Link', 'url' => 'https://google.com'],
      ],
      [
        //окно "выбор чата" куда закинуть бота + встроенную команду
        ['text' => 'ButtonSwitchInlineQuery', 'switch_inline_query' => ''],

        //вставить имя бота в текущий чат + встроенную команду
        ['text' => 'ButtonSwitchInlineQueryCurrentChat', 'switch_inline_query_current_chat' => ''],
      ],

    ],
  ]);
}

//курл
class Telegram {

  const DEBUG = 0;

  //запуск команд
  public function run($token, $method, $data) {

    if (self::DEBUG) {
      file_put_contents('telegram.txt', 'Запрос ' . json_encode($data) . "\n\n", FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/' . $method);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
  }

  //поток из чата
  public function flow() {
    $content = file_get_contents("php://input");

    if (self::DEBUG) {
      file_put_contents('telegram.txt', 'Ответ ' . $content . "\n\n", FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);
    }

    return json_decode($content, TRUE);
  }
}

//ответы
class Answer {

  public function __construct($data) {
    $this->data = $data;
  }

  //Нажали на Старт
  public function isStart() {
    if ($this->data['message']['text'] == '/start') {
      return 1;
    }
    return 0;
  }

  //нажали на обычную кнопку
  public function isButton($text) {
    if ($this->data['message']['text'] == $text) {
      return 1;
    }
    return 0;
  }

  //нажали на кнопку Callback
  public function isCallback($text) {
    if ($this->data['callback_query']['data'] == $text) {
      return 1;
    }
    return 0;
  }

  //текст запроса
  public function getText() {
    return $this->data['message']['text'];
  }

  //данные юзера
  public function user() {

    //from - кому лично
    //chat - в какой чат

    //$this->data['message']['from']['id'];
    //$this->data['message']['chat']['id'];

    //$this->data['callback_query']['message']['from']['id'];
    //$this->data['callback_query']['message']['chat']['id'];

    $user = new stdClass;

    if ($this->data['message']) {
      $user->id = $this->data['message']['chat']['id'];
      $user->first_name = $this->data['message']['chat']['first_name'];
      $user->username = $this->data['message']['chat']['username'];
      return $user;
    }

    if ($this->data['callback_query']) {
      $user->id = $this->data['callback_query']['message']['chat']['id'];
      $user->first_name = $this->data['callback_query']['message']['chat']['first_name'];
      $user->username = $this->data['callback_query']['message']['chat']['username'];
      return $user;
    }

  }

}

//реклама
class Ads {
  public static function variant1() {
    return 'ads';
  }

  public static function checkTime() {
    return 'пауза 3 сек';
  }
}

//ограничения по времени
class Throttle extends Db {

  //проверить запрос на время
  public function checkTime($bot, $user) {

    $sql = $this->db->prepare("SELECT * FROM `users` 
      WHERE `bots_id` = :bots_id 
      AND `chat_id` = :chat_id 
      AND NOW() > `next_request`
      ");

    $sql->execute([
      'bots_id' => $bot['id'],
      'chat_id' => $user->id,
    ]);

    return empty($sql->fetch());
  }

  //добавить N секунд
  public function addTime($bot, $user, $throttle) {

    $sql = $this->db->prepare("UPDATE `users` 
      SET `next_request` = NOW() + INTERVAL " . $throttle . " SECOND 
      WHERE `bots_id` = :bots_id 
      AND `chat_id` = :chat_id 
    ");

    $sql->execute([
      'bots_id' => $bot['id'],
      'chat_id' => $user->id,
    ]);
  }

}

?>

Ссылки
?start=123 откроет бот и передаст при старте "/start 123"
?startgroup откроет бот и окно "выбор чата" куда закинуть бота