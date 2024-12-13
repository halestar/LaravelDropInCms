<?php

namespace halestar\LaravelDropInCms\Widgets;

use halestar\LaravelDropInCms\Plugins\DiCmsWidget;
use Illuminate\Support\Facades\Blade;

class PageViewsCounterWidget implements DiCmsWidget
{

	/**
	 * @inheritDoc
	 */
    public static function widgetName(): string
    {
        return __('dicms::admin.widget.page_view_counter');
    }

	/**
	 * @inheritDoc
	 */
    public static function widgetIcon(): string
    {
        return '<?xml version="1.0" encoding="utf-8"?><svg fill="#000000" viewBox="0 0 24 24" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color"><path id="primary" d="M20,4H15a2,2,0,0,0-2,2V18a2,2,0,0,0,2,2h5a2,2,0,0,0,2-2V6A2,2,0,0,0,20,4Zm0,14H15V6h5ZM11,6V18a2,2,0,0,1-2,2H3a1,1,0,0,1,0-2H9V13H4a1,1,0,0,1,0-2H9V6H3A1,1,0,0,1,3,4H9A2,2,0,0,1,11,6Z" style="fill: rgb(0, 0, 0);"></path></svg>';
    }

	/**
	 * @inheritDoc
	 */
    public static function widgetDescription(): string
    {
        return __('dicms::admin.widget.page_view_counter.desc');
    }

	/**
	 * @inheritDoc
	 */
    public static function widgetControlHtml(): string
    {
        return '<div class="dicms-counter-container"><i class="dicms-counter-icon fa fa-eye"></i>' .
                '<span class="dicms-counter-counter">XXXXX</span><p class="dicms-counter-label">' .
                __('dicms::admin.widget.page_view_counter.views') . '</p></div>';
    }

	/**
	 * @inheritDoc
	 */
    public static function widgetHtml(): string
    {
        return "<x-page-views-counter :page=\"\$page\" />";
    }

	/**
	 * @inheritDoc
	 */
    public static function widgetConfig(): string
    {
        return Blade::render("<livewire:page-views-counter-config />");
    }

    public static function widgetId(): string
    {
        return 'page-views-counter';
    }
}
