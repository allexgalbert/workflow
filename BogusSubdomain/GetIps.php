<?php

//скачивание файла с ip адресами

include_once 'Curl.php';
include_once 'Database.php';

class GetIps {

  //скачивание файла
  static function download() {

    $curl = new Curl();
    $page = $curl::getpage('url/file.txt');

    self::handle($page);
  }

  //обработка массива ip адресов
  static function handle($page) {

    //файл в массив строк
    $page = explode("\n", $page);

    $ips = [];

    foreach ($page as $v) {

      $v = trim($v);

      //если пустой
      if (empty($v)) {
        continue;
      }

      // IP указан в формате XXX.XXX.XXX.XXX-XXX.XXX.XXX.XXX
      if (preg_match("~\-~isu", $v) == 1) {

        //массив 2 IP
        $range = explode('-', $v);

        //каждый массив делим на октеты
        $start = explode('.', $range[0]);
        $end = explode('.', $range[1]);

        //собираем
        $ips[] = self::assemblyips($start, $end);

      } // IP указан в формате XXX.XXX.X.X/XX
      elseif (preg_match("~/~isu", $v) == 1) {

        //массив IP и маска
        $cidr = explode('/', $v);

        $range = [];
        $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);

        //каждый массив делим на октеты
        $start = explode('.', $range[0]);
        $end = explode('.', $range[1]);

        //прибавим к первому IP (для последнего октета) +1, и отнимем от второго IP (для последнего октета) -1
        $start[3] = (string)($start[3] + 1);
        $end[3] = (string)($end[3] - 1);

        //собираем
        $ips[] = self::assemblyips($start, $end);

      } else {
        $ips[] = $v;
      }

    }

    //сольем все массивы в 1
    $result = [];
    foreach ($ips as $v) {
      if (is_array($v)) {
        foreach ($v as $v2) {
          $result[] = $v2;
        }
      } else {
        $result[] = $v;
      }

    }

    //переводим IP XXX.XXX.XXX.XXX в числа
    foreach ($result as &$ip) {
      $ip = ip2long($ip);
    }
    unset($ip);

    //очистить таблицу ip адресов
    self::ipsdelete();

    //вставить новые ip адреса
    self::ipsinsert($result);
  }

  //очистить таблицу ip адресов
  static function ipsdelete() {
    $database = new Database();
    $database->ipsdelete();
  }

  //вставить новые ip адреса
  static function ipsinsert($ips) {
    $database = new Database();
    $database->ipsinsert($ips);
  }

  //сборка массива адресов из октетов первого и последнего IP
  static function assemblyips($start, $end) {
    $ips = [];

    for ($i0 = $start[0]; $i0 <= $end[0]; $i0++) {
      for ($i1 = $start[1]; $i1 <= $end[1]; $i1++) {
        for ($i2 = $start[2]; $i2 <= $end[2]; $i2++) {
          for ($i3 = $start[3]; $i3 <= $end[3]; $i3++) {
            $ips[] = $i0 . '.' . $i1 . '.' . $i2 . '.' . $i3;
          }
        }
      }
    }

    return $ips;
  }

}