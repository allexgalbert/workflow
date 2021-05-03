<?php

//проверка условий

include_once 'Database.php';

class CheckConditions {

  //проверка ip
  static function checkip($ip) {
    $database = new \Database();
    return $database->checkip($ip);
  }

  //проверка юзерагента
  static function checkuseragent($checkuseragent) {
    $database = new \Database();
    return $database->checkuseragent($checkuseragent);
  }

  //проверка реферрера
  static function checkreferer($referer) {
    return !empty($referer);
  }

}