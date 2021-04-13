<?php

//сбор профилей, с групп

echo '<html><head><title>2_parse_profiles</title><head><html>';

require_once 'vendor/vk/VK.php';
require_once 'vendor/vk/VKException.php';
require_once 'vendor/db.php';

$db = new \Db();

$config = array(
  'app_id' => 123,
  'api_secret' => '123',
);

// id групп
$groups = array(
  'XXX',
);
$groups = array_unique($groups);

foreach ($groups as $group) {
  echo '<a target="_blank" href="https://vk.com/club' . $group . '">https://vk.com/club' . $group . '</a><br>';
}

// id стран
$countries = array(
  1 => 'Россия',
);

// id городов
$cities = array(
  1 => 'XXX',
);

//все найденные юзеры
$all_users = array();

//юзеры, подходящие под параметры
$target_users = array();

foreach ($groups as $group) {

  try {
    $vk = new VK\VK($config['app_id'], $config['api_secret']);

    $i = 0;
    do {
      $users = $vk->api('groups.getMembers', array(
        'group_id' => $group,
        'offset' => $i,
        'count' => 1000,
        'fields' => 'country, city, sex, bdate, last_seen'
      ));
      $i += 1000;

      foreach ($users['response']['users'] as $user) {

        //бан
        if (isset($user['deactivated'])) {
          continue;
        }

        //страна не та
        if (!in_array($user['country'], array_keys($countries))) {
          continue;
        }

        //город не тот
        if (!in_array($user['city'], array_keys($cities))) {
          continue;
        }

        //последний раз заходил не позже 2 недель назад
        if ((time() - $user['last_seen']['time']) > (60 * 60 * 24 * (7 * 2))) {
          continue;
        }

        //год рождения в промежутке 1980-1997
        if (isset($user['bdate'])) {
          $birth = explode('.', $user['bdate']);
          if (isset($birth[2]) and ($birth[2] < 1980 or $birth[2] > 1997)) {
            continue;
          }
        }

        //1-жен, 2-муж
        if ($user['sex'] == 1) {

          //вставка в базу
          $db->profile_insert($user);
        }
      }

    } while ($users['response']['users']);

  } catch (VK\VKException $error) {
    echo $error->getMessage();
  }
}

echo '<br>' . 'всего ' . count($all_users) . '<br>';
echo 'под параметры ' . count($target_users) . '<br>';
echo 'done';