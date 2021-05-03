<?php

//получаем данные юзера

class GetUserData {

  //юзерагент
  static function useragent() {
    return $_SERVER['HTTP_USER_AGENT'];
  }

  //реферрер
  static function referer() {
    return $_SERVER['HTTP_REFERER'];
  }

  //ip адрес
  static function ip() {
    if (!empty($_SERVER['REMOTE_ADDR'])) {
      $ip = $_SERVER['REMOTE_ADDR'];

    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];

    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return filter_var($ip, FILTER_VALIDATE_IP);

  }

}