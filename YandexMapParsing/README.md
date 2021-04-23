# Сбор информации об организациях с Яндекс-Карт

- По запросам типа "Москва магазины, Самара аптеки"
- Собираются данные: название, описание, адрес, сайт, категория, телефон, расписание, координаты
- Данные вставляются в базу.

# Примеры выборок

## Выбрать аптеки
```sql
SELECT * FROM `object` WHERE `category` REGEXP 'Аптека' AND `address` REGEXP 'Москва';
SELECT `category`, COUNT(*) FROM `object` GROUP BY `category`;
```

## Все уникальные категории, с сортировкой по кол-ву объектов в каждой
```sql
SELECT DISTINCT `category`, COUNT(`id`) as `quallityobjects`
FROM `object`
GROUP BY `category`
ORDER BY `quallityobjects` DESC;
```

## Все уникальные категории, с сортировкой по кол-ву объектов в каждой, где объектов больше 1000
```sql
SELECT DISTINCT `category`, COUNT(`id`) as `quallityobjects`
FROM `object`
GROUP BY `category`
HAVING `quallityobjects` >= 1000
ORDER BY `quallityobjects` DESC
```

## Все уникальные категории, с сортировкой по кол-ву объектов в каждой, у которых есть мыла
```sql
SELECT DISTINCT `category`, COUNT(`id`) AS `quallityobjects`
FROM `object`
WHERE LENGTH(`email`)>1
GROUP BY `category`
ORDER BY `quallityobjects` DESC;
```

## Все объекты по данной категории
```sql
SELECT `name`, `address`, `url`
FROM `object`
WHERE `category` = 'Банки';
```

## Все объекты с телефонами
```sql
SELECT COUNT(*) FROM `object` WHERE LENGTH(`phone`) > 1;
```

## Количество пройденных запросов
```sql
SELECT DISTINCT COUNT(*) FROM `city_has_type`;
SELECT DISTINCT COUNT(*) FROM `city_has_brand`;
```

## Количество объектов
```sql
SELECT COUNT(*) FROM `object`;
```

## Все объекты, у которых есть сайты
```sql
SELECT COUNT(*) FROM `object` WHERE LENGTH(`url`) > 1;
```

## Все объекты по данным категориям
```sql
SELECT *
FROM `object`
WHERE
`category` IN ('Автосервис, автотехцентр', 'Юридические услуги', 'Детский сад')
AND
LENGTH(`phone`) > 1;
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