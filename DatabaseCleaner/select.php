<?php

require 'mysql.php';
$dir = 'results/';
$db = new Mysql;
$config = $db->getconfig();

//файл со словами
$words = file('words1.txt', FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($words == FALSE || empty($words)) {
  exit('нет файла или он пустой' . N);
}

//удаляем пробелы и псевдопустые строки
foreach ($words as $k => &$v) {
  $v = trim($v);
  if (empty($v)) {
    unset($words[$k]);
  }
}
$words = array_values(array_unique($words));
echo N . 'Всего слов: ' . count($words) . N;
echo '====================================================' . N . N;

$alfavit = [
  "а" => "a",
  "б" => "b",
  "в" => "v",
  "г" => "g",
  "д" => "d",
  "ж" => "j",
  "з" => "z",

  "е" => "e",
  "ё" => "e",
  "э" => "e",
  "и" => "i",
  "й" => "i",
  "ы" => "i",
  "ш" => "sh",
  "щ" => "sh",

  "к" => "k",
  "л" => "l",
  "м" => "m",
  "н" => "n",
  "о" => "o",
  "п" => "p",
  "р" => "r",
  "с" => "s",
  "т" => "t",
  "у" => "y",
  "ф" => "f",
  "х" => "h",
  "ц" => "c",
  "ч" => "ch",
  "ю" => "u",
  "я" => "ya",

  "А" => "A",
  "Б" => "B",
  "В" => "V",
  "Г" => "G",
  "Д" => "D",
  "Ж" => "J",
  "З" => "Z",

  "Е" => "E",
  "Ё" => "E",
  "Э" => "E",
  "И" => "I",
  "Й" => "I",
  "Ы" => "I",
  "Ш" => "Sh",
  "Щ" => "Sh",

  "К" => "K",
  "Л" => "L",
  "М" => "M",
  "Н" => "N",
  "О" => "O",
  "П" => "P",
  "Р" => "R",
  "С" => "S",
  "Т" => "T",
  "У" => "Y",
  "Ф" => "F",
  "Х" => "H",
  "Ц" => "C",
  "Ч" => "Ch",
  "Ю" => "U",
  "Я" => "Ya",

  "ь" => "",
  "Ь" => "",
  "ъ" => "",
  "Ъ" => "",

  " " => "",
  "\\" => "",
  "|" => "",
  '"' => "",
  ":" => "",
  "/" => "",
  "?" => "",
  ">" => "",
  "<" => "",
  ";" => "",
  "'" => "",
  "=" => "",
  "-" => "",
  "`" => "",
  "," => "",
  "." => "",
  "&" => "",
  "*" => "",
  "(" => "",
  ")" => "",
  "_" => "",
  "+" => "",
  "~" => "",
  "!" => "",
  "№" => "",
  "@" => "",
  "#" => "",
  "$" => "",
  "%" => "",
  "^" => "",
  "{" => "",
  "}" => "",
  "[" => "",
  "]" => "",
];


//генерация запросов

//порядковый номер слова
$j = 0;

//проход по словам
foreach ($words as $word) {

  //показывает текущее слово в обработке
  echo N . ' ----- ' . ++$j . '. "' . $word . '":' . N;

  //сначала экранируем указанные символы слешем. потом эти слеши еще раз экранируем. потом символ " отдельно экранируем одним слешем, она пересекается с кавычкой в которой REGEXP.
  $word = addcslashes($word, '\^$.[]|()*+?{},-!=<>:');
  $word = addcslashes($word, '\\');
  $word = addcslashes($word, '"');

  $file = strtr($word, $alfavit);

  //укоротим длинну названия файла до 100 символов
  $file = substr($file, 0, 100);

  //создание пустого файла
  file_put_contents($dir . DB . '_' . $file . '_' . $j . '.txt', 'слово: ' . $word . N . N, FILE_USE_INCLUDE_PATH | LOCK_EX);

  //проход по таблицам
  foreach ($config as $table => $fields) {

    //echo ' - ' . $table . N; // показывает каждую текущую таблицу в обработке

    //проход по полям таблиц
    foreach ($fields as $field) {

      $sql = 'SELECT `' . $field['field'] . '` FROM `' . $table . '` WHERE `' . $field['field'] . '` REGEXP "' . $word . '";';

      //выполнение запроса
      $query = mysqli_query($db->connect, $sql) or die(mysqli_errno() . ' ' . mysqli_error());
      while ($row = mysqli_fetch_assoc($query)) {

        //показывает только таблицу в обработке у которой есть успешный запрос
        static $printtable = 0;
        if ($printtable == 0) {
          echo N . ' - ' . $table . N;
          ++$printtable;
        }

        $i = 0;

        //$kkk - имя поля. $vvv - значение
        foreach ($row as $kkk => $vvv) {

          //результат запроса
          $results[] = $vvv;

          //размер массива успешных результатов
          echo ++$i . ',';
        }
        //echo N;
      }
    }
    $printtable = 0;
  }

  echo N;

  if (empty($results)) {
    continue;
  }

  //отсечение дублей
  $results = array_values(array_unique($results));

  //запись в файл
  $div = '';
  foreach ($results as $vvv2) {
    $div .= $vvv2;
    $div .= N . N . '===========================================================================================' . N . N;
  }

  //заполнение файла
  file_put_contents($dir . DB . '_' . $file . '_' . $j . '.txt', $div, FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);
  $results = [];
}
echo N . N . 'Done' . N;