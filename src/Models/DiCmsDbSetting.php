<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\Plugins\DiCmsSetting;
use halestar\LaravelDropInCms\Traits\BackUpable;
use Illuminate\Database\Eloquent\Model;

class DiCmsDbSetting extends Model implements DiCmsSetting
{
    use BackUpable;

    protected static function getTablesToBackup(): array { return [ config('dicms.table_prefix') . "settings" ]; }
    protected function casts()
    {
        return
        [
            'value' => 'array',
        ];
    }

    protected $primaryKey = "key";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('dicms.table_prefix') . "settings";
        parent::__construct($attributes);
    }

    public static function get($key, $default = null): mixed
    {
        $setting = DiCmsDbSetting::where('key', $key)->first();
        if(!$setting)
            return $default;
        return $setting->value;
    }

    public static function set($key, mixed $value): void
    {
        //we do it the long way to take advantage of the casting. Upserts won't do that, I'm pretty sure :-)
        $setting = DiCmsDbSetting::where('key', $key)->first();
        if(!$setting)
        {
            $setting = new DiCmsDbSetting();
            $setting->key = $key;
        }
        $setting->value = $value;
        $setting->save();
    }
}
