<?php
/**
 * This comes from https://stackoverflow.com/questions/69793557/how-to-get-all-values-of-an-enum-in-php
 */

namespace halestar\LaravelDropInCms\Traits;


use halestar\LaravelDropInCms\Classes\MetadataEntry;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasMetadata
{

  public static function metadata(): Attribute
  {
      return Attribute::make(
          get: function(?string $value)
          {
              if(!$value) return [];
              $data = [];
              $value = json_decode($value, true);
              foreach($value as $val)
                  $data[] = new MetadataEntry($val['name'], $val['content']);
              return $data;
          },
          set: function(?array $value)
          {
              if(!$value) return json_encode([]);
              $data = [];
              foreach($value as $val)
                  $data[] = $val->toArray();
              return json_encode($data);
          }
      );
  }


}
