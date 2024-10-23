<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $page->Title() }}</title>
    @if($site->favicon)
        <link rel="icon" href="{{ $site->favicon }}" />
    @endif


    @if(!$page->override_css)
    @foreach($site->siteCss()->links()->get() as $css)
        <link href="{{ $css->href }}" {{ $css->link_type }} />
    @endforeach
    @endif

    @foreach($page->pageCss()->links()->get() as $css)
        <link href="{{ $css->href }}" {{ $css->link_type }} />
    @endforeach
    <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.css', ['page' => $page->id]) }}">

    @if($page->Header())
        <style>
            {!! $page->Header()->css !!}
        </style>
    @endif

    @if($page->Footer())
        <style>
            {!! $page->Footer()->css !!}
        </style>
    @endif

    <style>
        {!! $page->projectCss() !!}
    </style>

    @if(!$page->override_js)
        @foreach($site->siteJs()->links()->get() as $js)
            <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
        @endforeach
    @endif
    @foreach($page->pageJs()->links()->get() as $js)
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


@if($page->Header())
    {!! $page->Header()->html !!}
@endif


{!! $page->projectHtml() !!}

@if($page->Footer())
    {!! $page->Footer()->html !!}
@endif
@if($site->tag)
</{{ $site->tag }}>
@endif
<link href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.js', ['page' => $page->id]) }}" >
</body>
</html>
