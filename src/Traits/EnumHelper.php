<?php
/**
 * This comes from https://stackoverflow.com/questions/69793557/how-to-get-all-values-of-an-enum-in-php
 */

namespace halestar\LaravelDropInCms\Traits;


trait EnumHelper
{

  public static function names(): array
  {
    $names = array_column(self::cases(), 'name');
    sort($names);
    return $names;
  }

  public static function array(): array
  {
      $names = array_column(self::cases(), 'name');
      sort($names);
      return array_combine($names, $names);
  }

}
