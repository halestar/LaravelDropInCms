@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true])

@section('index_content')
        <div class="ms-1 row">
            <div class="col-4">{{ __('dicms::admin.name') }}</div>
            <div class="col-8">{{ __('dicms::admin.description') }}</div>
        </div>
        <div class="list-group">
            @foreach($widgets as $widget)
                <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.widgets.config', ['widget' => $widget::widgetId()]) }}" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-4">{{ $widget::widgetName() }}</div>
                        <div class="col-8 text-muted small">{{ $widget::widgetDescription() }}</div>
                    </div>
                </a>
            @endforeach
        </div>
@endsection
