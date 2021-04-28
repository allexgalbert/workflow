# Сбор информации об организациях с Яндекс-Карт

- По запросам типа "Москва магазины, Самара аптеки"
- Собираются данные: название, описание, адрес, сайт, категория, телефон, расписание, координаты
- Данные вставляются в базу

# Примеры выборок

## Выбрать аптеки в Москве

```sql
SELECT *
FROM `objects`
WHERE `category` REGEXP 'Аптека' AND `address` REGEXP 'Москва';
```

## Все категории с сортировкой по кол-ву объектов в каждой

```sql
SELECT `category`, COUNT(*) as `quallityobjects`
FROM `objects`
GROUP BY `category`
ORDER BY `quallityobjects` DESC;
```

## Все категории с сортировкой по кол-ву объектов в каждой, где объектов больше 1000

```sql
SELECT `category`, COUNT(*) as `quallityobjects`
FROM `objects`
GROUP BY `category`
HAVING `quallityobjects` >= 1000
ORDER BY `quallityobjects` DESC;
```

## Все категории с сортировкой по кол-ву объектов в каждой, у которых есть почта

```sql
SELECT `category`, COUNT(*) AS `quallityobjects`
FROM `objects`
WHERE LENGTH(`email`) > 1
GROUP BY `category`
ORDER BY `quallityobjects` DESC;
```

## Все объекты по данной категории

```sql
SELECT `name`, `address`, `url`
FROM `objects`
WHERE `category` = 'Банки';
```

## Все объекты с телефонами

```sql
SELECT COUNT(*)
FROM `objects`
WHERE LENGTH(`phone`) > 1;
```

## Все объекты у которых есть сайты

```sql
SELECT COUNT(*)
FROM `objects`
WHERE LENGTH(`url`) > 1;
```

## Все объекты по выбранным категориям

```sql
SELECT *
FROM `objects`
WHERE `category` IN ('Автосервис, автотехцентр', 'Юридические услуги', 'Детский сад');
```

## Вывод яндекс-карты

```php
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

<div id="map_' . $v->properties->CompanyMetaData->id . '" style="width: 600px; height: 400px"></div>

<script type="text/javascript">
ymaps.ready(init);
var myMap, myPlacemark;

function init() {
  myMap = new ymaps.Map("map_' . $v->properties->CompanyMetaData->id . '", {
    center: [' . $v->geometry->coordinates[1] . ', ' . $v->geometry->coordinates[0] . '],
    zoom: 17
  });

  myPlacemark = new ymaps.Placemark([' . $v->geometry->coordinates[1] . ', ' . $v->geometry->coordinates[0] . '], {
    hintContent: "Москва",
    balloonContent: "Столица России"
  });

  myMap.geoObjects.add(myPlacemark);
}
</script>
```

## Структура

![Сбор информации об организациях с Яндекс-Карт](https://raw.githubusercontent.com/allexgalbert/workflow/main/YandexMapParsing/1.png "Сбор информации об организациях с Яндекс-Карт")