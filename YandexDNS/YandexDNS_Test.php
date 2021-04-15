<?php

require_once 'vendor/autoload.php';
require_once 'HTTP/Request2.php';
$domain = 'domain.ru';

//Получение списка доменов
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/domain/domains?on_page=20', HTTP_Request2::METHOD_GET);
$request->setHeader(['PddToken' => 'PddToken']);
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Подключить домен
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/domain/register', HTTP_Request2::METHOD_POST);
$request->setHeader(['PddToken' => 'PddToken']);
$request->setBody('domain=' . $domain);
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Получить статус подключения домена
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/domain/registration_status?domain=' . $domain, HTTP_Request2::METHOD_GET);
$request->setHeader(['PddToken' => 'PddToken']);
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Получить настройки домена
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/domain/details?domain=' . $domain, HTTP_Request2::METHOD_GET);
$request->setHeader(['PddToken' => 'PddToken']);
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Удалить домен
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/domain/delete', HTTP_Request2::METHOD_POST);
$request->setHeader(['PddToken' => 'PddToken']);
$request->setBody('domain=' . $domain);
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Получить DNS-записи домена
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/list?domain=' . $domain, HTTP_Request2::METHOD_GET);
$request->setHeader(['PddToken' => 'PddToken']);
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Добавить DNS-запись
$types = ['SRV', 'TXT', 'NS', 'MX', 'SOA', 'A', 'AAAA', 'CNAME'];
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/add', HTTP_Request2::METHOD_POST);
$request->setHeader(['PddToken' => 'PddToken']);
$request->setBody('domain=' . $domain . '&type=' . $types[5] . '&content=127.0.0.1');
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Редактировать DNS-запись
$types = ['SRV', 'TXT', 'NS', 'MX', 'SOA', 'A', 'AAAA', 'CNAME'];
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/edit', HTTP_Request2::METHOD_POST);
$request->setHeader(['PddToken' => 'PddToken']);
$request->setBody('domain=' . $domain . '&type=' . $types[5] . '&record_id=1&content=127.0.0.1');
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());

//Удалить DNS-запись
$types = ['SRV', 'TXT', 'NS', 'MX', 'SOA', 'A', 'AAAA', 'CNAME'];
$request = new HTTP_Request2('https://pddimp.yandex.ru/api2/admin/dns/del', HTTP_Request2::METHOD_POST);
$request->setHeader(['PddToken' => 'PddToken']);
$request->setBody('domain=' . $domain . '&record_id=1');
$responce = $request->send();
$headers = $responce->getHeader();
$body = json_decode($responce->getBody());