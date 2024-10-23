    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>{{ $site->title }}</title>
        @if($site->favicon)
            <link rel="icon" href="{{ $site->favicon }}" />
        @endif


        @if(!$plugin::overridesCss($path))
            @foreach($site->siteCss()->links()->get() as $css)
                <link href="{{ $css->href }}" {{ $css->link_type }} />
            @endforeach
            <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.css.site', ['site' => $site->id]) }}">
        @endif

        @foreach($plugin::getCssFiles($path)->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Link) as $css)
            <link href="{{ $css->href }}" {{ $css->link_type }} />
        @endforeach
        <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.css.plugin', ['plugin' => $plugin, 'path' => $path]) }}">

        @if($plugin::getHeader($path))
            <style>
                {!! $plugin::getHeader($path)->css !!}
            </style>
        @elseif($site->defaultHeader()->exists())
            <style>
                {!! $site->defaultHeader->css !!}
            </style>
        @endif

        @if($plugin::getFooter($path))
            <style>
                {!! $plugin::getFooter($path)->css !!}
            </style>
        @elseif($site->defaultFooter()->exists())
            <style>
                {!! $site->defaultFooter->css !!}
            </style>
        @endif

        @if(!$plugin::overridesJs($path))
            @foreach($site->siteJs()->links()->get() as $js)
                <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
            @endforeach
        @endif

        @foreach($plugin::getJsFiles($path)->where('type', '=', \halestar\LaravelDropInCms\Enums\HeadElementType::Link) as $js)
            <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
        @endforeach
    </head>
    <body
    @if($site->body_attr)
        {!! $site->body_attr !!}
        @endif
    >
    @if($site->tag)
        <{{ $site->tag }} {!! $site->options !!}>
    @endif




    @if($plugin::getHeader($path))
            {!! $plugin::getHeader($path)->html !!}
    @elseif($site->defaultHeader)
            {!! $site->defaultHeader->html !!}
    @endif


    {!! $plugin::getPublicContent($path) !!}

    @if($plugin::getFooter($path))
        <footer {!! $plugin::getFooter($path)->options !!} >
            {!! $plugin::getFooter($path)->html !!}
        </footer>
    @elseif($site->defaultFooter)
        <footer {!! $site->defaultFooter->options !!} >
            {!! $site->defaultFooter->html !!}
        </footer>
    @endif
    @if($site->tag)
    </{{ $site->tag }}>
@endif

    <script src="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.js.site', ['site' => $site->id]) }}"></script>
    <script src="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.js.plugin', ['plugin' => $plugin, 'path' => $path]) }}"></script>

    </body>
</html>




