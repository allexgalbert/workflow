<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

  public function index() {

    $this->exchange1->send([
      'email' => 'email@email.com',
      'title' => 'title',
      'text' => 'text'
    ]);

    $this->exchange2->send([
      'email' => 'email@email.com',
      'title' => 'title',
      'text' => 'text'
    ]);

  }

  public function __construct() {
    parent::__construct();
  }

}