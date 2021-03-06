<?php

// парсинг картинок с пабликов вконтакте

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require 'vendor/autoload.php';
require 'libs/simple_html_dom.php';
require 'libs/curl.php';
require 'libs/db.php';

$host = 'http://localhost:4444/wd/hub';
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);
$driver->get('https://vk.com/fun_troll');

// прокрутка сразу много страниц
for ($i = 0; $i < 100; $i++) {
  $driver->findElement(WebDriverBy::tagName('body'))->sendKeys([WebDriverKeys::END]);
}
sleep(120);

$imgs_global = [];

for ($i = 0; $i <= 0; $i++) {
  echo '<br>' . $i . '-й скрол' . '<br>';

  $page = $driver->getPageSource();
  $html = new \simple_html_dom();
  $html->load($page);

  $imgs = [];
  foreach ($html->find('img.page_post_thumb_sized_photo') as $img) {

    if (!in_array($img->src, $imgs_global)) {
      $imgs[$img->src] = $img->src;
    }
    $imgs_global[] = $img->src;
  }

  $html->clear();
  unset($html);

  foreach ($imgs as $img) {
    echo '<img style="height: 200px;"  src="' . $img . '">';

    $pathinfo = pathinfo($img);
    $curl = \Curl::getpage($img); // скачиваем картинку в строку
    if ($curl['getinfo']['http_code'] != 200) {
      echo('код ответа сервера не 200');
      continue;
    }

    $sha1file = sha1($curl['content']); // хеш строки картинки

    $db = new \Db();
    $lastInsertId = $db->insert_img($sha1file, $pathinfo['extension'], $img);

    if ($lastInsertId != 0) {
      $folder = 'imgs'; // папка с которой работаем
      $generate_folder = intval(floor($lastInsertId / 32000)); // папка которая генерируется, в зависимости от имени файла
      $fullpath = $folder . '/' . $generate_folder;
      if (!file_exists($fullpath)) {
        mkdir($fullpath, 0777); // создаем папку
      }
      file_put_contents($fullpath . '/' . $lastInsertId . '.' . $pathinfo['extension'], $curl['content'],
        FILE_USE_INCLUDE_PATH | LOCK_EX);

      echo 'новая ';
    } else {
      echo 'старая ';
    }

    unset($db);
  }

  $driver->findElement(WebDriverBy::tagName('body'))->sendKeys([WebDriverKeys::END]);
  sleep(5);
}

$driver->quit();