<?php

class Fiosgen {

  private $alfavit = array(
    "а" => "a",
    "б" => "b",
    "в" => "v",
    "г" => "g",
    "д" => "d",
    "е" => "e",
    "ё" => "yo",
    "ж" => "j",
    "з" => "z",
    "и" => "i",
    "й" => "i",
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
    "ш" => "sh",
    "щ" => "sh",
    "ы" => "i",
    "э" => "e",
    "ю" => "u",
    "я" => "ya",
    "А" => "A",
    "Б" => "B",
    "В" => "V",
    "Г" => "G",
    "Д" => "D",
    "Е" => "E",
    "Ё" => "Yo",
    "Ж" => "J",
    "З" => "Z",
    "И" => "I",
    "Й" => "I",
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
    "Ш" => "Sh",
    "Щ" => "Sh",
    "Ы" => "I",
    "Э" => "E",
    "Ю" => "U",
    "Я" => "Ya",
    "ь" => "",
    "Ь" => "",
    "ъ" => "",
    "Ъ" => ""
  );

  //генератор имени и фамилии
  public function fio($count) {

    $name = file('female/name', FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $surname = file('female/surname', FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    for ($i = 0; $i < $count; $i++) {
      $fios[$i]['name'] = trim($name[array_rand($name)]);
      $fios[$i]['surname'] = trim($surname[array_rand($surname)]);
    }

    return $fios;
  }

  //генератор логина по имени и фамилии
  public function login($fio) {

    $rands = array(

      //год рождения
      rand(1960, 1990),

      //имя
      strtolower(strtr($fio['name'], $this->alfavit)),

      //имя + год рождения
      strtolower(strtr($fio['name'], $this->alfavit)) . rand(1960, 1990)
    );

    //фамилия + вариант
    return strtolower(strtr($fio['surname'], $this->alfavit)) . trim($rands[array_rand($rands)]);
  }

  //генератор пароля по имени и фамилии
  public function password1($fio) {
    $rands = array(strtolower(strtr($fio['name'], $this->alfavit)));
    return trim($rands[array_rand($rands)]) . strtolower(strtr($fio['surname'], $this->alfavit));
  }

  //генератор пароля случайный
  public function password2() {
    $allsubbol = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $passw = '';

    //кол-во символов пароля
    for ($i = 0; $i < 10; $i++) {
      $passw .= $allsubbol[array_rand($allsubbol)];
    }
    return $passw;
  }

  //генератор номера паспорта
  public function passport() {
    $allsubbol = range(0, 9);
    $passpnum = '';

    //кол-во чисел номера пасспорта
    for ($i = 0; $i < 6; $i++) {
      $passpnum .= $allsubbol[array_rand($allsubbol)];
    }
    return '8805' . $passpnum;
  }

}

$fiosgen = new Fiosgen();
$fios = $fiosgen->fio(10);

echo 'Имя и Фамилия | Логин | Пароль | Пароль | Паспорт' . "\n";
echo '- | - | - | - | -' . "\n";

foreach ($fios as $v) {
  echo $v['name'] . ' ' . $v['surname'] . ' | ';
  echo $fiosgen->login($v) . ' | ';
  echo $fiosgen->password1($v) . ' | ';
  echo $fiosgen->password2() . ' | ';
  echo $fiosgen->passport() . "\n";
}