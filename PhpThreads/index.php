<?php

require 'lockfile.php';

//Разблокировка неразблокированных объектов в бд, которым больше N минут
"UPDATE `objects` SET `lock` = NULL WHERE NOW() > (TIMESTAMP(`objects`.`lock`) + INTERVAL 20 MINUTE)";

//Блокировка файла на запись
$lockfile = new Lockfile ('object.lock');
$lockfile->lock();

//Выбор одного неотработанного объекта из бд
$id = "SELECT * FROM `objects` WHERE `objects`.`lock` IS NULL AND `objects`.`onoff` = '0' ORDER BY RAND() LIMIT 1";

//Блокировка этого объекта в бд
"UPDATE `objects` SET `lock` = NOW() WHERE `id` = $id";

//Разблокировка файла
$lockfile->unlock();
unset ($lockfile);

//Полезная работа с объектом
echo $id;

//Установка флага отработанному объекта в бд
"UPDATE `objects` SET `onoff` = '1' WHERE `id` = $id";

//Разблокировка объекта в бд
"UPDATE `objects` SET `lock` = NULL WHERE `id` = $id";