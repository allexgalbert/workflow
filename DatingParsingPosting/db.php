<?php

define('HOSTNAME', '');
define('DATABASE', '');
define('USERNAME', '');
define('PASSWORD', '');

class Db {

  public function __construct() {

    try {

      $this->db = new PDO (
        'mysql:host=' . HOSTNAME . ';dbname=' . DATABASE,
        USERNAME,
        PASSWORD,
        [
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

          // от ошибки "2006 MySQL server has gone away" (в настройках сервера выставить wait_timeout = 300)
          PDO::ATTR_PERSISTENT => true,
        ]
      );

    } catch (PDOException $e) {
      exit ($e->getMessage());
    }
  }

  //вставить профиль в базу
  public function profile_insert($profile) {
    $insert = $this->db
      ->prepare("INSERT IGNORE INTO `profiles` (`url`, `sex`, `city`) VALUES (:url, :sex, :city)");
    $insert->execute([
      'url' => $profile['url'],
      'sex' => $profile['sex'],
      'city' => $profile['city'],
    ]);
    return $this->db->lastInsertId();
  }


  //выбрать профиль, на который еще не постили с данного аккаунта
  public function profile_select($accounts_id, $sex) {
    $sql = "
SELECT `profiles`.`id`, `profiles`.`url` 
FROM `profiles`
WHERE
`sex` = '" . $sex . "'
AND

NOT EXISTS (
  SELECT * 
  FROM `accounts_has_profiles`
  WHERE
  `accounts_id` = " . $accounts_id . " 
  AND 
  `profiles_id` = `profiles`.`id`
)
 
ORDER BY  `profiles`.`id` DESC
LIMIT 1";
    return $this->db->query($sql)->fetch();
  }

  //удалить профиль
  public function profile_delete($id) {
    $delete = $this->db->prepare("DELETE FROM `profiles` WHERE `id` = :id");
    $delete->execute(['id' => $id]);
  }

  //вставить факт что на этот профиль запостили, с данного аккаунта
  public function accountshasprofiles_insert($profile_id, $account_id) {
    $insert = $this->db->prepare("INSERT IGNORE INTO `accounts_has_profiles` (`profiles_id`, `accounts_id`) VALUES (:profiles_id, :accounts_id)");
    $insert->execute([
      'profiles_id' => $profile_id,
      'accounts_id' => $account_id,
    ]);
    return $this->db->lastInsertId();
  }

  //выбрать аккаунт для постинга
  public function account_select($sex) {
    $sql = "SELECT * FROM `accounts` WHERE `onoff` = '1' AND `sex` = '" . $sex . "' ORDER BY RAND() LIMIT 1";
    return $this->db->query($sql)->fetch();
  }

  //обновить счетчик мессаг у аккаунта
  public function account_update($id) {
    $update = $this->db->prepare("UPDATE `accounts` SET `countprofiles` = `countprofiles` + 1 WHERE `id` = :id;");
    $update->execute(['id' => $id]);
  }

  //получить счетчик мессаг у аккаунта
  public function account_countprofiles($id) {
    $sql = "SELECT `countprofiles` FROM `accounts` WHERE `id` = " . $id;
    return $this->db->query($sql)->fetchColumn();
  }

}