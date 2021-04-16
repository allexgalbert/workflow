<?php

//подключать этот файл в консольных скриптах
//require 'IncludePath.php';

header("Content-Type: text/html; charset=utf-8");

switch (php_sapi_name()) {

  case 'apache2handler' :
    define('N', '<br>');
    break;

  case 'cli' :
    define('N', "\n");
    break;

  default:
    exit ('не определен интерфейс');
}

echo N . '----------[ ' . date('d.m.Y H:i:s:u') . ' ]----------' . N;

error_reporting(E_ALL ^ E_DEPRECATED);
set_time_limit(0);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'en_US.utf-8');

define('GLOBAL_PATH', 'folder');

set_include_path(
  implode(PATH_SEPARATOR, [
    get_include_path(),
    GLOBAL_PATH,
    GLOBAL_PATH . '/lib',
  ])
);