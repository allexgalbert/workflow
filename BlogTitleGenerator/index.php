<?php

class Gettitle {

  //тематика
  public function title_gen($theme_id) {

    $neutral = file('neutral', FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $theme = file('theme' . $theme_id, FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $skellet = file('skellet' . $theme_id, FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $skelett_rand = trim($skellet[array_rand($skellet)]);

    $title = '';

    for ($i = 0; $i < 10; $i++) {

      if (empty($title)) {
        $title = preg_replace("~(\[theme\])~is", trim($theme[array_rand($theme)]), $skelett_rand, 1);
        $title = preg_replace("~(\[neutral\])~is", trim($neutral[array_rand($neutral)]), $skelett_rand, 1);
      } else {
        $title = preg_replace("~(\[theme\])~is", trim($theme[array_rand($theme)]), $title, 1);
        $title = preg_replace("~(\[neutral\])~is", trim($neutral[array_rand($neutral)]), $title, 1);
      }
    }

    unset($i);
    return $title;
  }

}

$titles = new Gettitle();

for ($i = 0; $i < 10; $i++) {
  echo $titles->title_gen(1) . "\n";
}