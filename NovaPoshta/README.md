# Работа с API Нова Пошта

- Нова Пошта это сервис доставки почты на Украине https://novaposhta.ua
- Для магазина на базе CodeIgniter реализован функционал в личном кабинете клиента
- Создание и редактирование адреса на основе выбора областей, городов, и отделений
- Данные собираются по API и сохраняются в базу

## Контроллер

```php
//адрес
$data['address'] = $this->model_user->get_address($id);

//города
$data['cities'] = $this->model_geo->get_cities($data['address']->id_region);

//отделения
$data['postoffices'] = $this->model_geo->get_postoffices($data['address']->id_city);
	  
//области
$data['regions'] = $this->model_geo->get_regions();
```

## Обработчики

```javascript
//выбор области
$('select[name=id_region]').on('change', function () {

  //область
  var region = $(this);

  //очищаем селект городов и отделений
  $('select[name=id_city]').empty();
  $('select[name=id_postoffice]').empty();

  $.post('/address/get_cities',
    {
      region_id: region.val(),
    },
    function (result) {

      //вставка городов
      $('select[name=id_city]').append(result.cities);

      //в отделение вставим пустое поле
      $('select[name=id_postoffice]').append("<option value=''>Выберите</option>");
    });
});

//выбор города
$('select[name=id_city]').on('change', function () {

  //город
  var city = $(this);

  //очищаем селект отделений
  $('select[name=id_postoffice]').empty();

  $.post('/address/get_postoffices',
    {
      postoffice_id: city.val(),
    },
    function (result) {

      //вставка отделений
      $('select[name=id_postoffice]').append(result.postoffices);

    });
});
```

## Получение городов

```php
//получить все города, по id области
public function get_cities() {
  json_print([
    'cities' => $this->load->view('cities', [
      'cities' => $this->model_geo->get_cities($this->input->post('region_id'))
    ], true)
  ]);
}
```

## Шаблон cities

```php
<option value=''>Выберите</option>
<?php foreach ($cities as $v) { ?>
  <option value='<?= $v->city_id ?>'><?= $v->name ?></option>
<?php } ?>
```

## Получение отделений

```php
//получить все отделения, по id города
public function get_postoffices() {
  json_print([
    'postoffices' => $this->load->view('postoffices', [
      'postoffices' => $this->model_geo->get_postoffices($this->input->post('postoffice_id'))
    ], true)
  ]);
}
```

## Шаблон postoffices

```php
<option value=''>Выберите</option>
<?php foreach ($postoffices as $v) { ?>
  <option value='<?= $v->id ?>'><?= $v->name ?></option>
<?php } ?>
```