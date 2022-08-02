<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Workers extends CI_Controller {

  //воркер exchange1 queue1
  public function exchange1() {
    $this->rabbitmq->exchange1();
  }

  //воркер exchange2 queue1
  public function exchange2() {
    $this->rabbitmq->exchange2();
  }

  public function __construct() {
    parent::__construct();
  }
}