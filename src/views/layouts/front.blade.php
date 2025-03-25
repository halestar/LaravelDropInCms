<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        @foreach($page->getMetadata() as $meta)
            {!! $meta->toHTML() !!}
        @endforeach
        <title>{{ $page->Title() }}</title>
        @if($site->favicon)
            <link rel="icon" href="{{ $site->favicon }}" />
        @endif

        @foreach($page->Css() as $css)
            <link href="{{ $css->href }}" {!! $css->link_type !!} />
        @endforeach
        <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsPublicCss($page) }}">

        @if($page->Header())
            <style>
                {!! $page->Header()->css !!}
            </style>
        @else

        @endif

        @if($page->Footer())
            <style>
                {!! $page->Footer()->css !!}
            </style>
        @endif

        <style>
            {!! $page->projectCss() !!}
        </style>

        @foreach($page->Js() as $js)
            <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
        @endforeach

    </head>
    <body @if($site->body_attr) {!! $site->body_attr !!} @endif >
        @if($site->tag)
            <{{ $site->tag }} {!! $site->options !!}>
        @endif

            @if($page->Header())
                {!! $page->Header()->projectHtml($page) !!}
            @endif


            {!! $page->projectHtml() !!}

            @if($page->Footer())
                {!! $page->Footer()->projectHtml($page) !!}
            @endif

        @if($site->tag)
        </{{ $site->tag }}>
        @endif

        <script src="{{ \halestar\LaravelDropInCms\DiCMS::dicmsPublicJs($page) }}" ></script>
    </body>
</html>
