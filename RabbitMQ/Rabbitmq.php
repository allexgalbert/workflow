<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';

/*
cd /var/www/domain
php composer require php-amqplib/php-amqplib
php composer show
 */

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq {

  public function send($task) {

    //задача
    $param = json_decode($task);

    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

    //создать канал
    $channel = $connection->channel();

    //создать обменник
    //типы обменников direct, topic, headers, fanout
    //4й параметр true. чтобы задачи не удалялись при падении сервера
    $channel->exchange_declare($param->exchange,
      'direct',
      false,
      false,
      false);

    //создать очередь
    //3й параметр true. чтобы задачи не удалялись при падении сервера
    $channel->queue_declare($param->queue, false, true, false, false);

    //связать обменник и очередь
    $channel->queue_bind($param->queue, $param->exchange);

    //создать задачу
    //DELIVERY_MODE_PERSISTENT задачи сохраняются на диск. чтобы пережили падение сервера
    $task = new AMQPMessage($task, [
      'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]);

    //отправить задачу в обменник
    $channel->basic_publish($task, $param->exchange);

    //отправить задачу в очередь
    //2й параметр '' это обменник по умолчанию
    //$channel->basic_publish($task, '', $queue1);

    $channel->close();
    $connection->close();
  }

  //обработка задачи
  public function exchange1() {

    $queue = 'queue1';

    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

    //создать канал
    $channel = $connection->channel();

    //создать очередь
    //3й параметр true. чтобы задачи не удалялись при падении сервера
    $channel->queue_declare($queue, false, true, false, false);

    //установить prefetch_count = 1
    $channel->basic_qos(null, 1, null);

    //прослушать очередь
    //4й параметр false. для подтверждения выполнения задачи. без подтверждения задача будет отдана другому
    $channel->basic_consume($queue, '', false, false, false, false, [$this, 'callback1']);

    while ($channel->is_open()) {
      $channel->wait();
    }

    $channel->close();
    $connection->close();
  }

  //обработка задачи
  public function exchange2() {

    $queue = 'queue1';

    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

    //создать канал
    $channel = $connection->channel();

    //создать очередь
    //3й параметр true. чтобы задачи не удалялись при падении сервера
    $channel->queue_declare($queue, false, true, false, false);

    //установить prefetch_count = 1
    $channel->basic_qos(null, 1, null);

    //прослушать очередь
    //4й параметр false. для подтверждения выполнения задачи. без подтверждения задача будет отдана другому
    $channel->basic_consume($queue, '', false, false, false, false, [$this, 'callback2']);

    while ($channel->is_open()) {
      $channel->wait();
    }

    $channel->close();
    $connection->close();
  }

  //колбек
  public function callback1($task) {

    //задача
    $param = json_decode($task->body);

    //работа

    //отправка подтверждения выполнения задачи
    $task->ack();
    //$task->delivery_info['channel']->basic_ack($task->delivery_info['delivery_tag']);
    //$task->getChannel()->basic_cancel($task->getConsumerTag());

    //остановка воркера
    exec("kill {$param->pid}");
  }

  //колбек
  public function callback2($task) {

    //задача
    $param = json_decode($task->body);

    //работа

    //отправка подтверждения выполнения задачи
    $task->ack();
    //$task->delivery_info['channel']->basic_ack($task->delivery_info['delivery_tag']);
    //$task->getChannel()->basic_cancel($task->getConsumerTag());

    //остановка воркера
    exec("kill {$param->pid}");
  }

  public function __construct() {
    $this->CI =& get_instance();
  }

}