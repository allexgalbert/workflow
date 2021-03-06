<?php

define('HOSTNAME', '');
define('DATABASE', '');
define('USERNAME', '');
define('PASSWORD', '');

class Db {

  public function __construct() {
    try {
      $this->db = new PDO ('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      exit ($e->getMessage());
    }
  }

  public function instagram_insert($url) {
    $insert = $this->db->prepare("INSERT IGNORE INTO `instagram` (`url`) VALUES (:url)");
    $insert->execute([
      'url' => $url,
    ]);
    return $this->db->lastInsertId();
  }

  public function instagram_check($url) {
    $sql = "SELECT `id` FROM `instagram` WHERE `url` = '" . $url . "' LIMIT 1";
    $result = $this->db->query($sql)->fetch();
    if (is_array($result)) {
      return true;
    } else {
      return false;
    }
  }

}