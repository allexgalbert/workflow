# Определение страны и города пользователя по IP на основе MaxMind, для фреймворка CodeIgniter

- MaxMind-DB-Reader устанавливается как PHP Extension
- Определение 2-значного ISO-код страны пользователя по его IP
- Автоматическое обновление базы GeoLite2-Country.mmdb

## Установить MaxMind-DB-Reader через Composer

composer require geoip2/geoip2

## Установить MaxMind-DB-Reader как PHP Extension

Этот вариант работает быстрее в 6-7 раз.

```sh
git clone https://github.com/maxmind/libmaxminddb
cd libmaxminddb
./bootstrap
./configure
make
make install
```

```sh
git clone https://github.com/maxmind/MaxMind-DB-Reader-php.git
cd MaxMind-DB-Reader-php/ext
phpize
./configure
make
make install
```

```sh
sh -c "echo /usr/local/lib >> /etc/ld.so.conf.d/local.conf"
ldconfig
sh -c 'echo -e "; Enable MaxMind GEOIP extension\n[maxmind]\nextension=maxminddb.so" > /etc/php.d/maxminddb.ini'
```

## Проверить

В phpinfo() должен быть блок настроек maxminddb.

## Использовать

- Установить пакет composer require geoip2/geoip2
- Сделать composer update
- Скачать с https://maxmind.com базу GeoLite2-Country.mmdb на сервер

## Пример

```php
require 'vendor/autoload.php';
use MaxMind\Db\Reader;

//2-значный ISO-код страны посетителя, по его IP
function getisobyip($ip) {

  $CI =& get_instance();
  $reader = new Reader('GeoLite2-Country.mmdb');

  try {
    $result = $reader->get($ip);
    $reader->close();
    return $result['country']['iso_code'];
  } catch (Exception $e) {
    return '';
  }
}
```

```php
require 'vendor/autoload.php';
use GeoIp2\Database\Reader;

//2-значный ISO-код страны посетителя, по его IP
function getisobyip($ip) {

  $CI =& get_instance();
  $reader = new Reader('GeoLite2-Country.mmdb');

  try {
    $result = $reader->country($ip);
    return $result->country->isoCode;
  } catch (Exception $e) {
    return '';
  }
}
```

```php
function debug($ip) {
  foreach (['','0','0.0.0.0','1.1.1.1','255.255.255.255','300.300.300.300','1000.1000.1000.1000','1000.1000.1000','1000.1000','1000.'] as $ip) {
    var_dump(getisobyip($ip));
  }
}
```

## Автоматическое обновление базы GeoLite2-Country.mmdb

Создать файл updateMmdb.sh с правами 0744

```sh
#!/bin/sh

date

folder=/var/maxmind

# удалить старые файлы
rm -f $folder/GeoLite2-Country.mmdb
rm -f $folder/GeoLite2-Country.tar.gz

# скачать новый файл отсюда https://dev.maxmind.com/geoip/geoip2/geolite2
cd $folder
wget https://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.tar.gz

# создать папку
mkdir $folder/unpack

# извлечь архив
tar xvzf GeoLite2-Country.tar.gz -C $folder/unpack --strip-components=1

# скопировать базу в нужное место
cp -f $folder/unpack/GeoLite2-Country.mmdb anotherFolder

# удалить старые файлы
rm -fr $folder/unpack
rm -f $folder/GeoLite2-Country.tar.gz

# рестарт апач
systemctl restart httpd.service
```

Запуск файла

```sh
0 0 1 * * /var/maxmind/updateMmdb.sh
```