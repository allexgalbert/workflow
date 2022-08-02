<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exchange1 {

  public function send($task) {

    //воркер exchange1 queue1
    $pid = exec(
      "php /var/www/domain/index.php rabbitmq workers exchange1 > /dev/null 2>&1 & echo $!;",
    );

    //echo "pid {$pid}\n";

    $this->CI->rabbitmq->send(
      json_encode([
        'exchange' => 'exchange1',
        'queue' => 'queue1',
        'pid' => $pid,
        'task' => $task,
      ])
    );

  }

  public function __construct() {
    $this->CI =& get_instance();
    $this->CI->load->library('/rabbitmq/rabbitmq');
  }

}