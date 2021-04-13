<?php

class Lockfile {

  //открыть файл на запись
  public function __construct($path) {
    $this->file = fopen($path, 'a');
  }

  //блокировать файл на запись
  public function lock() {
    flock($this->file, LOCK_EX);
  }

  //разблокировать файл
  public function unlock() {
    flock($this->file, LOCK_UN);
  }

  //закрыть файл
  public function __destruct() {
    fclose($this->file);
  }

}