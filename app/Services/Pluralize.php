<?php


namespace App\Services;


class Pluralize
{
  public static function getDeclension($count, $language)
  {
    if ($language === 'en') {
      if ($count > 1) {
        return 'few';
      }
      return 'one';
    }

    if ($count % 10 == 1 && $count % 100 != 11) {
      return 'one';
    }

    if (in_array($count % 10, [2, 3, 4]) && !in_array($count % 100, [12, 13, 14])) {
      return 'few';
    }

    return 'many';
  }
}
