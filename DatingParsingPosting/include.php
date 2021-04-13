<?php

switch (php_sapi_name()) {

  case 'apache2handler' :
    define('N', '<br>');
    break;

  case 'cli' :
    define('N', "\n");
    break;

  default:
    exit('не определен интерфейс');
}

echo N . '----------------------------- ' . date('H:i') . ' -----------------------------' . N . N;

error_reporting(E_ALL ^ E_DEPRECATED);
set_time_limit(0);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'en_US.utf-8');

define('GLOBAL_PATH', 'C:/domains/dating');

set_include_path(
  implode(
    PATH_SEPARATOR, [get_include_path(), GLOBAL_PATH]
  )
);