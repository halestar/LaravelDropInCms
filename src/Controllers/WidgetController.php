<?php

namespace halestar\LaravelDropInCms\Controllers;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\DiCMS;

class WidgetController extends Controller
{
	public function index()
	{
        $widgets = DiCMS::widgets();
        $template =
            [
                'title' => trans_choice('dicms::admin.widget', 2),
                'buttons' => []
            ];
        return view('dicms::widgets.index', compact('widgets', 'template'));
	}

    public function config($widget)
    {
        $widgetId = $widget;
        $selectedWidget = null;
        foreach(DiCMS::widgets() as $widget)
        {
            if($widget::widgetId() == $widgetId)
            {
                $selectedWidget = $widget;
                break;
            }
        }
        $widget = $selectedWidget;
        $template =
            [
                'title' => $widget::widgetName(),
                'buttons' =>
                    [
                        'back'  =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.widgets.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ],
                    ]
            ];
        return view('dicms::widgets.config', compact('widget', 'template'));
    }
}
