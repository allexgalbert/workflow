<?php

class Getdomain {

  //тематика
  public function domain_gen($theme_id) {

    $neutral = file('neutral', FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $theme = file('theme' . $theme_id, FILE_USE_INCLUDE_PATH | FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $rands = array(

      //нейтральное слово + тематическое слово
      trim($neutral[array_rand($neutral)]) . trim($theme[array_rand($theme)]),

      //нейтральное слово + - + тематическое слово
      trim($neutral[array_rand($neutral)]) . '-' . trim($theme[array_rand($theme)]),

      //тематическое слово + нейтральное слово
      trim($theme[array_rand($theme)]) . trim($neutral[array_rand($neutral)]),

      //тематическое слово + - + нейтральное слово
      trim($theme[array_rand($theme)]) . '-' . trim($neutral[array_rand($neutral)]),

      //тематическое слово + тематическое слово
      trim($theme[array_rand($theme)]) . trim($theme[array_rand($theme)]),

      //тематическое слово + - + тематическое слово
      trim($theme[array_rand($theme)]) . '-' . trim($theme[array_rand($theme)]),
    );

    return trim($rands[array_rand($rands)]);
  }

}

$domains = new Getdomain();
for ($i = 0; $i < 10; $i++) {
  echo $domains->domain_gen(3) . '.com' . "\n";
}