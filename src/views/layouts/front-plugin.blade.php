<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $site->title }}</title>

    @foreach($plugin['css']->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Link) as $css)
        <link href="{{ $css->href }}" {{ $css->link_type }} />
    @endforeach
    <style>
        {!! $plugin['css']->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Text)->pluck('sheet')->join("\n\n"); !!}
    </style>
    @if($plugin['header'])
        <style>
            {!! $plugin['header']->css !!}
        </style>
    @endif
    @if($plugin['footer'])
        <style>
            {!! $plugin['footer']->css !!}
        </style>
    @endif

    @foreach($plugin['js']->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Link) as $js)
        <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
    @endforeach
    <script>
        {!! $plugin['js']->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Text)->pluck('script')->join("\n\n"); !!}
    </script>

</head>
<body style="{{ $site->body_styles }}" class="{{ $site->body_classes }}">
@if($site->defaultMenu)
    <nav class="{!! $site->defaultMenu->nav_classes !!}">
        <ul class="{!! $site->defaultMenu->container_classes !!}">
            @foreach($site->defaultMenu->menu as $menuItem)
                <li class="{!! $site->defaultMenu->element_classes !!}">
                    <a
                        class="{!! $site->defaultMenu->link_classes !!}"
                        @if($menuItem['type'] == "LINK")
                            href="{{ $menuItem['url'] }}"
                        @elseif($menuItem['type'] == "PAGE")
                            href="{{ \halestar\LaravelDropInCms\Models\Page::url($menuItem['page_id']) }}"
                        @else
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsPublicRoute() . $menuItem['plugin_url'] }}"
                        @endif
                    >{!! $menuItem['name'] !!}</a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif
@if($plugin['header'])
    <header {!! $plugin['header']->options !!} >
        {!! $plugin['header']->html !!}
    </header>
@endif
<section>
    {!! $plugin['content'] !!}
</section>
@if($plugin['footer'])
    <footer {!! $plugin['footer']->options !!} >
        {!! $plugin['footer']->html !!}
    </footer>
@endif
</body>
</html>
