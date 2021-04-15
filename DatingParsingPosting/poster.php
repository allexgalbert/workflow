<?php

// Постинг на профиля

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\Chrome\ChromeOptions;
use voku\helper\HtmlDomParser;

require 'vendor/autoload.php';
require 'libs/db.php';

$config = [
  'im' => 'm',           // кто я
  'search' => 'f',       // кому пощу
  'countprofiles' => 1000, // на сколько профилей постим
];

$domain = 'https://dating';
$sleep = rand(1, 2);
$db = new \Db();

//аккаунт, с которого постим
$account = $db->account_select($config['im']);
if (empty($account)) {
  exit ('кончились аккаунты');
}

echo 'аккаунт №' . $account['id'] . N;

//стартуем браузер
$caps = DesiredCapabilities::chrome();
$options = new ChromeOptions();
$options->setBinary('C:\Program Files\Google\Chrome\Application/chrome.exe');
$caps->setCapability(ChromeOptions::CAPABILITY, $options);
$driver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $caps, 5000);

//открываем урл логина
try {
  $driver->get($domain . '/auth.php');
} catch (\Exception $e) {
  echo $e->getMessage();
  $driver->quit();
  exit ('неудачный логин');
}
sleep($sleep);

//логин
try {
  $driver->findElement(WebDriverBy::cssSelector('.auth[name=email]'))->sendKeys($account['login']);
  $driver->findElement(WebDriverBy::cssSelector('.auth[name=passwd]'))->sendKeys($account['pass']);
  $driver->findElement(WebDriverBy::cssSelector('.auth.flat'))->click();
} catch (\Exception $e) {
  echo $e->getMessage();
  $driver->quit();
  exit ('неудачный логин');
}
sleep($sleep);

//по N акков за раз
while ($db->account_countprofiles($account['id']) < $config['countprofiles']) {

  sleep($sleep);

  //профиль, на который постим
  $profile = $db->profile_select($account['id'], $config['search']);

  if (!$profile) {
    echo 'кончились профиля';
    break;
  }

  echo N . $profile['id'] . ': ' . $profile['url'] . ' ';

  //открываем урл профиля
  try {
    $driver->get($profile['url']);
  } catch (\Exception $e) {
    echo $e->getMessage();
    continue;
  }
  sleep($sleep);

  //забираем html страницы
  try {
    $page = $driver->getPageSource();
  } catch (\Exception $e) {
    echo $e->getMessage();
    continue;
  }

  if (preg_match("~404 ошибка~isu", $page, $match) == 1) {
    echo '404 ошибка';
    $db->profile_delete($profile['id']);
    continue;
  }

  //убрать все попапы
  for ($i1 = 0; $i1 < 10; $i1++) {
    try {
      $driver->getKeyboard()->sendKeys(WebDriverKeys::ESCAPE);
    } catch (\Exception $e) {
      continue;
    }
  }
  sleep($sleep);

  //забираем html страницы
  try {
    $page = $driver->getPageSource();
  } catch (\Exception $e) {
    echo $e->getMessage();
    continue;
  }

  //урл чата
  if (preg_match("~var cur_user_oid = '(.+?)'~isu", $page, $match) == 1) {
    $chat_popap = $domain . '/message.scroll.php?oid=' . $match[1];
  } else {
    echo 'нет урла чата';
    $db->profile_delete($profile['id']);
    continue;
  }

  $html = HtmlDomParser::str_get_html($page);

  //имя
  $name = $html->find('title', 0);

  if (preg_match("~^(.+?),~isu", $name->plaintext, $match) == 1) {
    $name = $match[1];
  } else {
    echo 'нет имени';
    continue;
  }

  unset($html);

  //открываем урл чата
  try {
    $driver->get($chat_popap);
  } catch (\Exception $e) {
    echo $e->getMessage();
    continue;
  }
  sleep($sleep);

  //текст мессаги
  $text = $account['message'];
  $text = preg_replace("~(NNN)~isu", $name, $text);
  $texts = preg_split("~={10}~isu", $text);
  $message = trim($texts[array_rand($texts)]);

  //постим
  try {

    //поле ввода
    $driver->findElement(WebDriverBy::id("message"))->sendKeys($message);
    sleep($sleep);

    //кнопка сабмит
    $driver->findElement(WebDriverBy::id('button_send_submit'))->click();

  } catch
  (\Exception $e) {
    echo $e->getMessage();
    echo 'попап не отправился';
    continue;
  }
  sleep($sleep);

  for ($i2 = 0; $i2 <= 5; $i2++) {

    //забираем html страницы
    try {
      $page = $driver->getPageSource();
    } catch (\Exception $e) {
      echo $e->getMessage();
      continue;
    }

    $html = HtmlDomParser::str_get_html($page);

    //признак отправки - пустой текст в поле мессаги
    $result = $html->find('#message', 0)->plaintext;

    if (empty(trim($result))) {

      $db->account_update($account['id']);

      $db->accountshasprofiles_insert($profile['id'], $account['id']);

      echo ' : ' . 'Сообщение отправлено';

      file_put_contents(
        'logs/poster.log',
        date('d.m H:i') . ': ' . 'Сообщение отправлено' . "\n",
        FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX
      );

      unset($html);
      break;
    }
    sleep($sleep);

    unset($html);
  }
}

$driver->quit();
echo N . N . 'done';