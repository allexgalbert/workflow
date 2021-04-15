<?php

//Csv to vcf

set_time_limit(0);

$contacts = file('1.csv', FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//удалим 1 строку с заголовками
unset($contacts[0]);

//собираем файл vcf
$file = '';
$n = "\n";

foreach ($contacts as $v) {

  list($name, $phone) = explode(';', $v);

  $name = trim($name);
  $phone = trim($phone);

  if ($name && $phone) {
    $file .= 'BEGIN:VCARD' . $n;
    $file .= 'VERSION:2.1' . $n;
    $file .= 'N;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:' . $name . $n;
    $file .= 'TEL;CELL:' . $phone . $n;
    $file .= 'END:VCARD' . $n;
  }

}

//файл
$filename = date('d-m-Y-H-i') . '.vcf';

//сохраняем
$file_put_contents = file_put_contents(
  '/path/' . $filename,
  $file,
  FILE_USE_INCLUDE_PATH | LOCK_EX
);

if ($file_put_contents) {
  echo $filename . ' сохранен' . "\n";
}