<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $site->title }}</title>

    @foreach($site->siteCss()->links()->get() as $css)
        <link href="{{ $css->href }}" {{ $css->link_type }} />
    @endforeach
    <style>
        {!! $site->siteCss()->text()->get()->pluck('sheet')->join("\n\n"); !!}
    </style>
    @if($site->defaultHeader)
        <style>
            {!! $site->defaultHeader->css !!}
        </style>
    @endif
    @if($site->defaultFooter)
        <style>
            {!! $site->defaultFooter->css !!}
        </style>
    @endif

    @foreach($site->siteJs()->links()->get() as $js)
        <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
    @endforeach
    <script>
        {!! $site->siteJs()->text()->get()->pluck('script')->join("\n\n"); !!}
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
@if($site->defaultHeader)
    <header {!! $site->defaultHeader->options !!} >
        {!! $site->defaultHeader->html !!}
    </header>
@endif
<section>
    {!! $plugin_content !!}
</section>
@if($site->defaultFooter)
    <footer {!! $site->defaultFooter->options !!} >
        {!! $site->defaultFooter->html !!}
    </footer>
@endif
</body>
</html>
