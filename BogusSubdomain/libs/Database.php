<?php

define('HOSTNAME', '');
define('PORT', '');
define('DATABASE', '');
define('USERNAME', '');
define('PASSWORD', '');

class Database {

  public function __construct() {

    try {

      $this->db = new PDO (
        'mysql:host=' . HOSTNAME . ';dbname=' . DATABASE . ';port=' . PORT,
        USERNAME,
        PASSWORD,
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
      );

      $this->db->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
      );

      $this->db->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
      );

    } catch (PDOException $e) {
      exit ($e->getMessage());
    }
  }

  //проверка ip
  public function checkip($ip) {
    $select = $this->db->prepare("SELECT `ip` FROM `ips` WHERE `ip` = :ip");
    $select->execute(['ip' => ip2long($ip)]);
    return $select->fetch();
  }

  //проверка юзерагента
  public function checkuseragent($useragent) {
    $select = $this->db->prepare("SELECT `useragent` FROM `useragents` WHERE `useragent` = :useragent");
    $select->execute(['useragent' => $useragent]);
    return $select->fetch();
  }

  //очистить таблицу ip адресов
  public function ipsdelete() {
    $delete = $this->db->prepare("DELETE FROM `ips`");
    $delete->execute();
  }

  //вставить новые ip адреса
  public function ipsinsert($ips) {
    foreach ($ips as $ip) {
      $insert = $this->db->prepare("INSERT IGNORE INTO `ips` (`ip`) VALUES (:ip)");
      $insert->execute(['ip' => $ip]);
    }
  }

}