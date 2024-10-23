<?php
/**
 * This comes from https://stackoverflow.com/questions/69793557/how-to-get-all-values-of-an-enum-in-php
 */

namespace halestar\LaravelDropInCms\Traits;


trait BackedEnumHelper
{

  public static function names(): array
  {
    return array_column(self::cases(), 'name');
  }

  public static function values(): array
  {
    return array_column(self::cases(), 'value');
  }

  public static function array(): array
  {
    return array_combine(self::values(), self::names());
  }

}
