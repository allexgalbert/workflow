<?php

require_once 'curl.php';

class Parse {

  public function getdata($phrase) {

    $options = [
      //'apikey' => 'XXX',
      'text' => urlencode($phrase),
      'lang' => 'ru_RU',
      'results' => 1000,
      'type' => 'biz',
    ];

    $options2 = '';
    foreach ($options as $k => $v) {
      $options2 .= '&' . $k . '=' . $v;
    }

    $url = 'https://search-maps.yandex.ru/v1/?' . $options2;
    $curl = Curl::getpage($url);

    if ($curl['getinfo']['http_code'] != 200) {
      file_put_contents('log.txt', 'код ответа сервера не 200' . "\n", FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);
      echo('код ответа сервера не 200');
      return;
    }

    $content = json_decode($curl['content']);

    var_dump($content);

    if ($content->status) {
      file_put_contents('log.txt', 'сервис ответил ' . $content->message . "\n", FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);
      echo('сервис ответил ' . $content->message);
      return;
    }

    $result = [];

    foreach ($content->features as $v) {

      $description = [];
      if (isset($v->properties->CompanyMetaData->Features)) {
        foreach ($v->properties->CompanyMetaData->Features as $v2) {
          $description[] = $v2->name;
        }
      }

      $result[] = [
        'place_id' => $v->properties->CompanyMetaData->id,
        'name' => $v->properties->CompanyMetaData->name,
        'address' => $v->properties->CompanyMetaData->address,
        'url' => $v->properties->CompanyMetaData->url,
        'category' => $v->properties->CompanyMetaData->Categories[0]->name,
        'phone' => $v->properties->CompanyMetaData->Phones[0]->formatted,
        'schedule' => $v->properties->CompanyMetaData->Hours->text,
        'description' => implode(', ', $description),
        'latitude' => $v->geometry->coordinates[1],
        'longitude' => $v->geometry->coordinates[0],
      ];

    }

    return $result;
  }
}