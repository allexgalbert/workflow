<?php

//Текущее время юзера, для его timezone

//список всех timezone для России
$russia_timezones = DateTimeZone::listIdentifiers(
  DateTimeZone::PER_COUNTRY,
  'RU'
);

//вывод текущего времени для каждой timezone
foreach ($russia_timezones as $russia_timezone) {
  date_default_timezone_set($russia_timezone);
  echo date_default_timezone_get() . ' ' . date('H:i');
}

//список timezone для России из википедии
$russia_timezones = [
  'Europe/Kaliningrad' => 'Калининградское время, MSK –1',
  'Europe/Moscow' => 'Московское время, MSK',
  'Europe/Samara' => 'Самарское время, MSK+1',
  'Asia/Yekaterinburg' => 'Екатеринбургское время, MSK+2',
  'Asia/Omsk' => 'Омское время, MSK+3',
  'Asia/Krasnoyarsk' => 'Красноярское время, MSK+4',
  'Asia/Irkutsk' => 'Иркутское время, MSK+5',
  'Asia/Yakutsk' => 'Якутское время, MSK+6',
  'Asia/Vladivostok' => 'Владивостокское время, MSK+7',
  'Asia/Magadan' => 'Магаданское время, MSK+8',
  'Asia/Kamchatka' => 'Камчатское время, MSK+9',
];
?>

  <!--вывод timezone в селекте-->
  <select>
    <option value="">Выберите время</option>
    <?php foreach ($russia_timezones as $russia_timezone => $humanity) { ?>
      <?php date_default_timezone_set($russia_timezone); ?>
      <option value="<?= date_default_timezone_get() ?>">
        <?= date('H:i') . ', ' . $humanity ?>
      </option>
    <?php } ?>
  </select>

<?php

//скрипт для GoogleMaps. по имени города или адреса:
//поолучить широту/долготу
//потом по широте/долготе получить timezone
//потом вывести текущее время юзера

$city = urlencode('Италия Милан');

$url1 =
  "https://maps.googleapis.com/maps/api/geocode/json?" .
  "address=" . $city . "&" .
  "sensor=false";

$result1 = getcurl($url1);

if ($result1->status == 'OK') {

  $latitude = $result1->results[0]->geometry->location->lat;
  $longitude = $result1->results[0]->geometry->location->lng;

  $latitude = str_replace(',', '.', $latitude);
  $longitude = str_replace(',', '.', $longitude);

  sleep(2);

  $url2 =
    "https://maps.googleapis.com/maps/api/timezone/json?" .
    "location=" . $latitude . "," . $longitude . "&" .
    "timestamp=1331161200&" .
    "key=AIzaSyAtpji5Vk271Qu6_QFSBXwK7wpoCQLY-zQ";

  $result2 = getcurl($url2);

  date_default_timezone_set($result2->timeZoneId);
  echo date_default_timezone_get() . ' ' . date('H:i');
}

function getcurl($url) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);

  $json_output = curl_exec($curl);
  curl_close($curl);
  return json_decode($json_output);
}