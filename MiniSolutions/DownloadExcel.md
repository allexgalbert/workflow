# Скачать файл Excel

## Вариант 1

```html
<form action='export_excel' method='get'>
  <input type="submit" value="Скачать">
</form>
```

## Вариант 2

```html
<button id="export" type="button">Скачать</button>
```

```javascript
$('#export').on('click', function () {

  //чекбоксы
  var orderIds = '';
  $('.check_print:checked').each(function () {
    orderIds = orderIds + $(this).attr('name') + ";";
  });

  if (orderIds.length > 0) {
    window.location.href = '/export?orderIds=' + orderIds;
  }

});
```

```php
function export($order) {

  $file = '';

  //заголовки свои названия
  $file .= 'Столбик1' . ';';
  $file .= 'Столбик2' . ';';

  //заголовки из полей
  $order_one = $orders[0];
  foreach ($order_one as $field => $value) {
    $file .= $field . ';';
  }
  $file .= "\n";

  //наполнение
  foreach ($orders as $order) {
    $file .= '"' . $order->id . '"' . ';';
    $file .= '"' . $order->name . '"' . ';';
    $file .= '"' . $order->cost . '"' . ';';
  }

  //название файла
  $filename = 'файл_' . date("d.m.Y_H_i") . '.csv';

  //сохранить
  file_put_contents('/path/' . $filename, iconv('utf-8', 'windows-1251', $file), FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX);

  //скачать
  header("Content-type: text/csv; charset=cp1251;");
  header("Content-disposition: attachment; filename=" . $filename);
  echo iconv('utf-8', 'windows-1251', $file);
}