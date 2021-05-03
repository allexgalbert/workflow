<?php

//получаем данные юзера
include_once 'GetUserData.php';

//домен
$domain = 'a.domain.com';

//-------------------------------------------------

function requesturi() {
  if (empty($_SERVER['REQUEST_URI'])) {
    return '';
  }
  return $_SERVER['REQUEST_URI'];
}

//-------------------------------------------------

//логгер юзерагентов
file_put_contents('useragents.txt', GetUserData::useragent() . "\n", FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);

//логгер реферреров
file_put_contents('referers.txt', GetUserData::referer() . "\n", FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);

//-------------------------------------------------

//проверка условий
include_once 'CheckConditions.php';

//ip из черного списка. покажем заглушку
if (CheckConditions::checkip(GetUserData::ip())) {
  exit ('500 error. запрещенной информации не содержится');
}

//поисковый бот и реферрер (поисковый бот кликнул на ссылку на стороннем сайте). покажем контент на домене
if (CheckConditions::checkuseragent(GetUserData::useragent()) && CheckConditions::checkreferer(GetUserData::referer())) {
  include_once 'Curl.php';
  $curl = new Curl();
  $page = $curl::getpage('http://' . $domain . requesturi());
  echo $page;
  exit;
}

//поисковый бот (поисковый бот перешел напрямую на страницу). покажем контент на домене
if (CheckConditions::checkuseragent(GetUserData::useragent())) {
  include_once 'Curl.php';
  $curl = new Curl();
  $page = $curl::getpage('http://' . $domain . requesturi());
  echo $page;
  exit;
}

//реферрер (юзер кликнул на ссылку на стороннем сайте). редирект на поддомен
if (CheckConditions::checkreferer(GetUserData::referer())) {
  header('Location: https://' . $domain . requesturi());
  exit;
}

//заход по HTTP и пустой реферрер (юзер зашел напрямую по http или кликнул на http-ссылку на стороннем сайте, но http отдал пустой реферрер). редирект на поддомен. Выключен редирект HTTP->HTTPS
if (empty($_SERVER['HTTPS']) && empty(GetUserData::referer())) {
  header('Location: https://' . $domain . requesturi());
  exit;
}

//если никакой случай не сработал. покажем заглушку
exit ('500 error. запрещенной информации не содержится');