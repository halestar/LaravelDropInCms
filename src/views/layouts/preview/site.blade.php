<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $site->title }}</title>
    @if($site->favicon)
        <link rel="icon" href="{{ $site->favicon }}" />
    @endif

    @foreach($site->siteCss()->links()->get() as $css)
        <link href="{{ $css->href }}" {{ $css->link_type }} />
    @endforeach
    <link rel="stylesheet" type="text/css" property="stylesheet" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.css.site') }}">

    @if($site->defaultHeader()->exists())
        <style>
            {!! $site->defaultHeader->css !!}
        </style>
    @endif

    @if($site->defaultFooter()->exists())
        <style>
            {!! $site->defaultFooter->css !!}
        </style>
    @endif

    @foreach($site->siteJs()->links()->get() as $js)
        <script src="{{ $js->href }}" {!! $js->link_type !!} ></script>
    @endforeach
    <link href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.preview.js.site', ['site' => $site->id]) }}" >

</head>
<body
    @if($site->body_attr)
        {!! $site->body_attr !!}
    @endif
>


@if($site->tag)
    <{{ $site->tag }} {!! $site->options !!}>
@endif


@if($site->defaultHeader)
    {!! $site->defaultHeader->html !!}
@endif

<section style="font-size: 3em;">
    {{ __('dicms::admin.preview.page_content') }}
</section>
@if($site->defaultFooter)
    {!! $site->defaultFooter->html !!}
@endif
@if($site->tag)
</{{ $site->tag }}>
@endif
</body>
</html>
