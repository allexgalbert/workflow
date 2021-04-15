<?php

// Парсинг профилей

use voku\helper\HtmlDomParser;

require 'vendor/autoload.php';
require 'libs/curl.php';
require 'libs/db.php';

$config = [
  'iam' => 'M',    //кто я
  'search' => 'F', //кого ищу
  'from' => 18,    //от N лет
  'to' => 30,      //до N лет
  'city' => '101_6675_101', //москва. 101_6675_0 подмосковье
];

$db = new \Db();

//колво профилей
$profiles_count = 0;

for ($i = 0; $i < 40784; $i = $i + 10) {

  //страница
  if (!$page1 = Curl::post(
    'https://dating.ru/search.php',
    http_build_query([

        'offset' => $i,
        'search_type' => 'Active',
        'key' => '',
        'start_time' => 1610293200,
        'action' => 1,
        'metros' => '',

        'ext_params[users][lookfor]' => strtoupper($config['search']),
        'ext_params[users][iam]' => strtoupper($config['iam']),

        'ext_params[users][age][from]' => $config['from'],
        'ext_params[users][age][to]' => $config['to'],

        'ext_params[users][city]' => $config['city'],

        //с фотографией
        'ext_params[users][photo]' => 'on',

        //с видео
        'ext_params[users][video]' => '',

        'ext_params[users][target]' => '',
        'ext_params[users][online_days]' => 0,

        'profile_sent_list' => 0,

        //active - активные, online - сейчас на сайте, all - все
        'ext_params[users][online]' => 'active',
      ]
    )
  )) {
    continue;
  }

  //парсим
  $html1 = HtmlDomParser::str_get_html($page1);

  //профили
  $profiles = [];

  foreach ($html1->find('#search_form > tr > td') as $v1) {

    //нет ссылки на профиль
    if (empty($v1->findOneOrFalse('a'))) {
      continue;
    }

    if (preg_match("~^/(.+?)/~isu", $v1->find('a', 0)->href, $match) == 1) {
      $profiles['https://dating.ru' . $match[0]] = [
        'url' => 'https://dating.ru' . $match[0],
        'sex' => $config['search'],
        'city' => $config['city'],
      ];
    }

  }

  $profiles_count += count($profiles);

  //вставляем
  foreach ($profiles as $profile) {
    $db->profile_insert($profile);
    echo $profile['url'] . N;
  }

  echo N;

  //если профилей нет то выброс
  if (empty($profiles)) {
    break;
  }

}

file_put_contents(
  'logs/parser.log',
  date('d.m H:i') . ': ' . $profiles_count . "\n",
  FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX
);

echo $profiles_count . ' найдено';