<?php
header("Content-Type: text/html; charset=utf-8");
?>

  <style type='text/css'>
    input {
      height: 40px;
      width: 49%;
      margin: 0px;
      padding: 0px;
    }
  </style>

  <form action='' method='post'>
    <input name='text' type='text' value='<?= $_POST['text'] ?>'>
    <input name='submit' type='submit' value='submit'>
  </form>

<?php
if (
  !isset($_POST['text']) || empty(trim($_POST['text']))
) {
  exit('введите фразу');
}

require 'parse.php';
$parse = new Parse();
$result = $parse->getdata($_POST['text']);