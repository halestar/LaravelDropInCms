@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true])

@section('index_content')
    {!! $widget::widgetConfig() !!}
@endsection
