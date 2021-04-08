<?php

require_once 'vendor/autoload.php';
require_once 'HTTP/Request2.php';

class YandexDNS {

  /**
   * @var токен
   */
  public $PddToken;

  /**
   * добавить домен в сервис
   * @param $domain
   * @return true
   * @throws Exception
   * @throws HTTP_Request2_LogicException
   */
  public function admin_adddomain($domain) {
    $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/domain/register', HTTP_Request2::METHOD_POST);
    $request->setHeader(array('PddToken' => $this->PddToken));
    $request->setBody('domain=' . $domain);
    $responce = $request->send();
    $body = json_decode($responce->getBody());
    return $body->status == 'added';
  }

  /**
   * удалить все DNS-записи
   * @param $domain
   * @return int кол-во удаленных записей
   * @throws Exception
   * @throws HTTP_Request2_LogicException
   */
  public function admin_dellalldns($domain) {
    $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/list?domain=' . $domain, HTTP_Request2::METHOD_GET);
    $request->setHeader(array('PddToken' => $this->PddToken));
    $responce = $request->send();
    $body = json_decode($responce->getBody());

    $result = 0;
    foreach ($body->records as $item) {
      $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/del', HTTP_Request2::METHOD_POST);
      $request->setHeader(array('PddToken' => $this->PddToken));
      $request->setBody('domain=' . $domain . '&record_id=' . $item->record_id);
      $responce = $request->send();
      $body = json_decode($responce->getBody());
      if ($body->success == 'ok') {
        $result += 1;
      }
    }
    return $result;
  }

  /**
   * добавить DNS-запись
   * @param $domain
   * @param $options
   * @return true
   * @throws Exception
   * @throws HTTP_Request2_LogicException
   */
  public function admin_adddns($domain, $options) {
    $options_string = '';
    foreach ($options as $key => $value) {
      $options_string .= '&' . $key . '=' . $value;
    }

    $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/add', HTTP_Request2::METHOD_POST);
    $request->setHeader(array('PddToken' => $this->PddToken));
    $request->setBody('domain=' . $domain . $options_string);
    $responce = $request->send();
    $body = json_decode($responce->getBody());
    return $body->success == 'ok';
  }

  /**
   * @var токен
   */
  public $Authorization;

  /**
   * добавить домен в сервис
   * @param $domain
   * @return true
   * @throws Exception
   * @throws HTTP_Request2_LogicException
   */
  public function registrant_adddomain($domain) {
    $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/registrar/domain/register', HTTP_Request2::METHOD_POST);
    $request->setHeader(array('PddToken' => $this->PddToken, 'Authorization' => $this->Authorization));
    $request->setBody('domain=' . $domain);
    $responce = $request->send();
    $body = json_decode($responce->getBody());
    return $body->status == 'added';
  }

  /**
   * удалить все DNS-записи
   * @param $domain
   * @return int кол-во удаленных записей
   * @throws Exception
   * @throws HTTP_Request2_LogicException
   */
  public function registrant_dellalldns($domain) {
    $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/registrar/dns/list?domain=' . $domain, HTTP_Request2::METHOD_GET);
    $request->setHeader(array('PddToken' => $this->PddToken, 'Authorization' => $this->Authorization));
    $responce = $request->send();
    $body = json_decode($responce->getBody());

    $result = 0;
    foreach ($body->records as $item) {
      $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/registrar/dns/del', HTTP_Request2::METHOD_POST);
      $request->setHeader(array('PddToken' => $this->PddToken, 'Authorization' => $this->Authorization));
      $request->setBody('domain=' . $domain . '&record_id=' . $item->record_id);
      $responce = $request->send();
      $body = json_decode($responce->getBody());
      if ($body->success == 'ok') {
        $result += 1;
      }
    }
    return $result;
  }

  /**
   * добавить DNS-запись
   * @param $domain
   * @param $options
   * @return true
   * @throws Exception
   * @throws HTTP_Request2_LogicException
   */
  public function registrar_adddns($domain, $options) {
    $options_string = '';
    foreach ($options as $key => $value) {
      $options_string .= '&' . $key . '=' . $value;
    }

    $request = new HTTP_Request2('https://pddimp.yandex.ru/api2/registrar/dns/add', HTTP_Request2::METHOD_POST);
    $request->setHeader(array('PddToken' => $this->PddToken, 'Authorization' => $this->Authorization));
    $request->setBody('domain=' . $domain . $options_string);
    $responce = $request->send();
    $body = json_decode($responce->getBody());
    return $body->success == 'ok';
  }

}

$b = new YandexDNS();
$b->PddToken = 'PddToken';
$b->Authorization = 'Authorization';
$b->registrant_adddomain('domain.ru');
$b->registrant_dellalldns('domain.ru');
$b->registrar_adddns('domain.ru', array('type' => 'A', 'content' => '127.0.0.1'));