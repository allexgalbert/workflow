<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exchange2 {

  public function send($task) {

    //воркер exchange2 queue1
    $pid = exec(
      "php /var/www/domain/index.php rabbitmq workers exchange2 > /dev/null 2>&1 & echo $!;",
    );

    //echo "pid {$pid}\n";

    $this->CI->rabbitmq->send(
      json_encode([
        'exchange' => 'exchange2',
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