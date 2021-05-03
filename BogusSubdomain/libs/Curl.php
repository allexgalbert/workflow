<?php

//Curl

class Curl {

  public static function getpage($url) {

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt(
      $curl,
      CURLOPT_USERAGENT,
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4148.0 Safari/537.36'
    );

    $content = curl_exec($curl);

    if (curl_errno($curl) == 0 && curl_getinfo($curl)['http_code'] == 200) {
      return $content;
    } else {
      return false;
    }

    curl_close($curl);

  }

}