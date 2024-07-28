@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center mb-2">
            <span>{{ $site->name }}: {{__('dicms::js_scripts.script.title')}}</span>
            <div>
                <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.create', ['site' => $site->id]) }}" role="button">{{ __('dicms::js_scripts.new') }}</a>
                <a class="btn btn-secondary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" role="button">{{ __('dicms::js_scripts.back') }}</a>
            </div>
        </h1>
        <ul class="list-group">
            @foreach($site->jsScripts as $script)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a
                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.edit', ['site' => $site->id, 'script' => $script->id]) }}"
                    class=""
                >
                    {{ $script->name }}
                </a>
                <button
                    type="button"
                    onclick="confirmDelete('{{ __('dicms::scripts.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.destroy', ['site' => $site->id, 'script' => $script->id]) }}')"
                    class="btn btn-danger"
                >{{ __('dicms::admin.delete') }}</button>
            @endforeach
        </ul>
    </div>
@endsection
