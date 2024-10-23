<?php
return
[
    'table_prefix' => env('DICMS_TABLE_PREFIX', 'dicms_'),
    'media_upload_disk' => env('DICMS_MEDIA_UPLOAD_DISK', 'public'),
    'thumb_folder' => env('DICMS_IMAGE_THUMB_FOLDER', '.thmb'),
    'thumb_max_height' => env('DICMS_IMAGE_THUMB_MAX_HEIGHT', 64),
    'img_max_height' => env('DICMS_IMAGE_MAX_HEIGHT', 768),
    'datetime_format' => env('DICMS_DT_FORMAT', 'm/d/Y h:i A'),
    'date_format' => env('DICMS_DATE_FORMAT', 'm/d/Y'),
    'back_to_url' => env('DICMS_BACK_TO_URL', '/'),
    'settings_class' => env('DICMS_SETTINGS_CLASS', \halestar\LaravelDropInCms\Models\DiCmsDbSetting::class),

    'policies' =>
    [
        \halestar\LaravelDropInCms\Models\Site::class => env('DICMS_SITE_POLICY', \halestar\LaravelDropInCms\Policies\SitePolicy::class),
        \halestar\LaravelDropInCms\Models\Header::class => env('DICMS_HEADER_POLICY', \halestar\LaravelDropInCms\Policies\HeaderPolicy::class),
        \halestar\LaravelDropInCms\Models\Footer::class => env('DICMS_FOOTER_POLICY', \halestar\LaravelDropInCms\Policies\FooterPolicy::class),
        \halestar\LaravelDropInCms\Models\CssSheet::class => env('DICMS_CSS_SHEET_POLICY', \halestar\LaravelDropInCms\Policies\CssSheetPolicy::class),
        \halestar\LaravelDropInCms\Models\JsScript::class => env('DICMS_JS_SCRIPT_POLICY', \halestar\LaravelDropInCms\Policies\JsScriptPolicy::class),
        \halestar\LaravelDropInCms\Models\Page::class => env('DICMS_PAGE_POLICY', \halestar\LaravelDropInCms\Policies\PagePolicy::class),
        \halestar\LaravelDropInCms\Models\DataItem::class => env('DICMS_DATA_ITEMS_POLICY', \halestar\LaravelDropInCms\Policies\DataItemPolicy::class),

    ],

    'plugins' => []
];
