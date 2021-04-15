<?php

//curl. скачать страницу

//$head = 0 без заголовков севера
//$head = 1 с заголовками сервера
//$body = 0 без тела страницы
//$body = 1 с телом страницы
//$verbose = 0 молчаливый режим
//$verbose = 1 подробный режим

class Curl {

  public static function getpage($url, $head = 0, $body = 1, $verbose = 0) {

    $curl = curl_init();

    //урл + протокол
    curl_setopt($curl, CURLOPT_URL, $url);

    //useragent
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64)');

    //сжатие
    //curl_setopt($curl, CURLOPT_ENCODING, '');

    //возвратить результат строкой в переменную curl_exec, а не в браузер
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    //возвратить сырой вывод
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);

    //молчаливый режим
    //curl_setopt($curl, CURLOPT_MUTE, 1);

    //позволить редиректы
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    //при редиректах подставлять в Referer значение Location
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);

    //возвратить false если код ответа сервера >=400
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);

    //подставить свой заголовок "Referer: "
    //curl_setopt($curl, CURLOPT_REFERER, 'referer');

    //установить свои заголовки
    //curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: text/plain', 'Content-length: 100']);

    //позволить авторизацию
    //curl_setopt($curl, CURLOPT_USERPWD, "myusername:mypassword");

    //число секунд ожидания успешного коннекта
    //curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

    //число секунд выполнения курла
    //curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    //возвратить http-заголовки вместе с телом страницы
    if ($head == 1) {
      curl_setopt($curl, CURLOPT_HEADER, 1);
    }

    //не возвращать тело страницы
    if ($body == 0) {
      curl_setopt($curl, CURLOPT_NOBODY, 1);
    }

    //подробный вывод обо всех действиях
    if ($verbose == 1) {
      curl_setopt($curl, CURLOPT_VERBOSE, 1);
    }

    //игнорить сессионные куки
    //curl_setopt($curl, CURLOPT_COOKIESESSION, 1);

    //отослать куки
    //curl_setopt($curl, CURLOPT_COOKIE, "name1=value1; name2=value2");

    //файл содержащий строку-куку
    //curl_setopt($curl, CURLOPT_COOKIEFILE, $file);

    //файл куда сохраняются несессионные куки после закрытия курла
    //curl_setopt($curl, CURLOPT_COOKIEJAR, $file);

    //вывод результата работы курла в файл
    //curl_setopt($curl, CURLOPT_FILE, $file);

    //ввод данных для работы курла из файла
    //curl_setopt($curl, CURLOPT_INFILE, $file);

    //полученные HTTP-заголовки в файл
    //curl_setopt($curl, CURLOPT_WRITEHEADER, $file);

    //отправить urlencode(post-данные) в формате "application/x-www-form-urlencoded"
    //curl_setopt($curl, CURLOPT_POST, 1);
    //curl_setopt($curl, CURLOPT_POSTFIELDS, "field1=value1&field2=value2");

    //загрузить файл
    //curl_setopt($curl, CURLOPT_UPLOAD, 1);
    //curl_setopt($curl, CURLOPT_FTPASCII, 1);

    //строка в котором файл как строка
    //curl_setopt($curl, CURLOPT_INFILE, $string);

    //сам файл
    //curl_setopt($curl, CURLOPT_INFILESIZE, filesize($file));

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); //без SSL сертификата
    //curl_setopt($curl, CURLOPT_CAINFO, $file); //файл SSL сертификата

    //прокси
    //curl_setopt($curl, CURLOPT_PROXY, $proxyurl);

    //прокси порт
    //curl_setopt($curl, CURLOPT_PROXYPORT, $proxyport);

    //авторизация на прокси
    //curl_setopt($curl, CURLOPT_PROXYUSERPWD, "username:password");

    //имя исходящего интерфейса для использования (IP\host\interface)
    //curl_setopt($curl, CURLOPT_INTERFACE, $ip);

    $content = curl_exec($curl);

    if (curl_errno($curl) == 0 && curl_getinfo($curl)['http_code'] == 200) {
      curl_close($curl);
      return $content;
    } else {

      $result = [
        'errno' => curl_errno($curl),
        'error' => curl_error($curl),
        'getinfo' => curl_getinfo($curl),
      ];

      curl_close($curl);

      return $result;
    }

  }

}