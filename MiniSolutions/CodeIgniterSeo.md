# Функционал для реализации SEO на фреймворке CodeIgniter

## В файле настроек application/config/config.php

```php
$config['meta'] = ['description' => '', 'keywords' => ''];
```

## В лайоте application/views/frontend/global/header.php

```html
<?= theme_seo() ?>
```

## В хелпере application/helpers/theme_headers_helper.php

```php
function theme_seo() {

  $headers_array = [];

  $CI =& get_instance();

  //description и keywords с контроллера
  $meta1 = $CI->temp->get('meta');

  //description и keywords с конфига
  $meta2 = $CI->config->item('meta');

  if (!empty($meta1['description'])) {
    $headers_array[] = '<meta name="description" content="' . $meta1['description'] . '">';
  } else {
    $headers_array[] = '<meta name="description" content="' . $meta2['description'] . '">';
  }

  if (!empty($meta1['keywords'])) {
    $headers_array[] = '<meta name="keywords" content="' . $meta1['keywords'] . '">';
  } else {
    $headers_array[] = '<meta name="keywords" content="' . $meta2['keywords'] . '">';
  }

  return implode("\n", $headers_array);
}
```

## В контроллере application/controllers/Home.php

```php
$this->temp->set('meta', [
  'description' => 'description',
  'keywords' => 'keywords',
  ]
);
```