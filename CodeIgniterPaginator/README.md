# Кнопка "Ещё" для каталога на фреймворке CodeIgniter

- Стандартная постраничная пагинация переписана на кнопку "Ещё"
- За основу взят пагинатор ядра фреймворка
- Кнопка работает корректно вместе со стандартной пагинацией
- Показывает оставшееся количество товара, который можно подгрузить

## Шаблон

```php
<div id='moreButtonWrap'>
  <?= $moreButton ?>
</div>
```

## Контроллер

```php
$data['moreButton'] = $this->pagination->createButton($GETparams);
```

## Пагинатор

Скопировать system/libraries/Pagination.php в application/libraries/Pagination.php

```php
public function createButton($GETparams = []) {

  //total_rows - всего строк
  //per_page - строк на страницу

  if ($this->total_rows == 0 || $this->per_page == 0) {
    return '';
  }

  //колво страниц
  $num_pages = (int)ceil($this->total_rows / $this->per_page);

  if ($num_pages === 1) {
    return '';
  }

  //GET-параметры
  if ($this->reuse_query_string === TRUE) {
    $get = $GETparams;
    unset($get['c'], $get['m'], $get[$this->query_string_segment]);
  } else {
    $get = [];
  }

  //base_url - полный урл без per_page
  //пример /catalog?id_category=9

  $base_url = trim($this->base_url);

  $query_string_sep = (strpos($base_url, '?') === FALSE) ? '?' : '&amp;';

  if ($this->page_query_string === TRUE) {
    $base_url .= $query_string_sep . http_build_query(array_merge($get, array($this->query_string_segment => '')));
  } else {

    if (!empty($get)) {
      $query_string = $query_string_sep . http_build_query($get);
      $this->suffix .= $query_string;
    }

    if ($this->reuse_query_string === TRUE && ($base_query_pos = strpos($base_url, '?')) !== FALSE) {
      $base_url = substr($base_url, 0, $base_query_pos);
    }

    $base_url = rtrim($base_url, '/') . '/';
  }

  $base_page = ($this->use_page_numbers) ? 1 : 0;

  if ($this->page_query_string === TRUE) {
    if (!empty($GETparams[$this->query_string_segment])) {
      $this->cur_page = $GETparams[$this->query_string_segment];
    }
  } elseif (empty($this->cur_page)) {
    if ($this->uri_segment === 0) {
      $this->uri_segment = count($this->CI->uri->segment_array());
    }

    $this->cur_page = $this->CI->uri->segment($this->uri_segment);

    if ($this->prefix !== '' or $this->suffix !== '') {
      $this->cur_page = str_replace(array($this->prefix, $this->suffix), '', $this->cur_page);
    }
  } else {
    $this->cur_page = (string)$this->cur_page;
  }

  if (!ctype_digit($this->cur_page) or ($this->use_page_numbers && (int)$this->cur_page === 0)) {
    $this->cur_page = $base_page;
  } else {
    $this->cur_page = (int)$this->cur_page;
  }

  if ($this->use_page_numbers) {
    if ($this->cur_page > $num_pages) {
      $this->cur_page = $num_pages;
    }
  } elseif ($this->cur_page > $this->total_rows) {
    $this->cur_page = ($num_pages - 1) * $this->per_page;
  }

  if (!$this->use_page_numbers) {
    $this->cur_page = (int)floor(($this->cur_page / $this->per_page) + 1);
  }

  //кнопка
  $output = '';

  if ($this->next_link !== FALSE && $this->cur_page < $num_pages) {

    $i = ($this->use_page_numbers) ? $this->cur_page + 1 : $this->cur_page * $this->per_page;

    $curpage = $this->cur_page + 1;
    $perpage = $i;
    $url = $base_url . $this->prefix . $i . $this->suffix;

    //счетчик. сколько еще осталось показать товаров
    $stillLeft = $this->total_rows - ($this->per_page * $this->cur_page);

    $output .= "<button id='moreButton' class='btn btn-warning' data-curpage='" . $curpage . "' data-perpage='" . $perpage . "' data-url='" . $url . "' data-text='Еще' data-wait='Ждите...'>
          Еще " . $stillLeft . " товаров
        </button>";

  }

  $output = preg_replace('#([^:"])//+#', '\\1/', $output);

  return $output;
}
```

## Обработчик

```javascript
$(document).delegate('#moreButton', 'click', function (event) {

  var button = $(this);

  //выключаем кнопку
  button
    .attr('disabled', 'disabled')
    .text(button.data('wait'));

  //запрос на сервер
  $.post('/catalog/moreButton',
    {
      curpage: button.data('curpage'),
      perpage: button.data('perpage'),
      url: button.data('url'),
    },
    function (result) {

      //вставляем товары
      $('#productionMore').append(result.production);

      //обновляем кнопку Еще
      $('#moreButtonWrap').html(result.moreButton);

      //обновляем обычную пагинацию
      $('#paginationWrap').html(result.pagination);

      //обновляем урл в браузере
      history.pushState(
        null,
        null,
        button.data('url')
      );

      //включаем кнопку
      button
        .removeAttr('disabled')
        .text(button.data('text'));
    });
});
```

## Обработчик

```php
public function moreButton() {

  $post = $this->input->post();
  //[curpage] => 2
  //[perpage] => 10
  //[url] => /catalog?per_page=10

  $parse_url = parse_url($post['url']);
  //[path] => /catalog
  //[query] => per_page=10

  parse_str($parse_url['query'], $GETparams);
  //[per_page] => 10

  json_print([
    'production' => $this->load->view('frontend/catalog/catalog', [
      'production' => $getProduction['production']
    ], true),
    'moreButton' => $getProduction['moreButton'],
    'pagination' => $getProduction['pagination'],
  ]);
}
```

## Демо

![Кнопка "Ещё" для каталога на фреймворке CodeIgniter](https://raw.githubusercontent.com/allexgalbert/workflow/main/CodeIgniterPaginator/1.gif "
Кнопка 'Ещё' для каталога на фреймворке CodeIgniter")