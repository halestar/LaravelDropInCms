<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $page->Title() }}</title>

    @foreach($page->Css()->links()->get() as $css)
        <link href="{{ $css->href }}" {{ $css->link_type }} />
    @endforeach
    <style>
        {!! $page->Css()->text()->get()->pluck('sheet')->join("\n\n"); !!}
    </style>

    @if($page->Header)
        <style>
            {!! $page->Header->css !!}
        </style>
    @endif
    @if($page->Footer)
        <style>
            {!! $page->Footer->css !!}
        </style>
    @endif

    @if($page->css)
        <style>
            {!! $page->css !!}
        </style>
    @endif

    @foreach($page->Js()->links()->get() as $js)
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
@if($page->Header)
    <header {!! $page->Header->options !!} >
        {!! $page->Header->html !!}
    </header>
@endif
<section>
    {!! $page->html !!}
</section>
@if($page->Footer)
    <footer {!! $page->Footer->options !!} >
        {!! $page->Footer->html !!}
    </footer>
@endif
</body>
</html>
