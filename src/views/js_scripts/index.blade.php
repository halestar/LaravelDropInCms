@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
    @if(\halestar\LaravelDropInCms\Models\JsScript::count() > 0)
        <div class="ms-1 row">
            <div class="col-2">{{ __('dicms::admin.name') }}</div>
            <div class="col-9">{{ __('dicms::admin.description') }}</div>
        </div>
        <ul class="list-group">
            @foreach(\halestar\LaravelDropInCms\Models\JsScript::all() as $script)
                <li class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-2">{{ $script->name }}</div>
                        <div class="col-8 text-muted small">{{ $script->description }}</div>
                        <div class="col-2 text-end">
                            @can('create', \halestar\LaravelDropInCms\Models\JsScript::class)
                                <a
                                    role="button"
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.edit', ['script' => $script->id]) }}"
                                    class="btn btn-primary btn-sm ms-2"
                                    title="{{ __('dicms::admin.edit') }}"
                                ><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('create', \halestar\LaravelDropInCms\Models\JsScript::class)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.duplicate', ['script' => $script->id]) }}"
                                    role="button"
                                    class="btn btn-warning btn-sm ms-2"
                                    title="{{ __('dicms::js_scripts.duplicate') }}"
                                ><i class="fa-solid fa-clone"></i></a>
                            @endcan
                            @can('delete', \halestar\LaravelDropInCms\Models\JsScript::class)
                                <button
                                    type="button"
                                    onclick="confirmDelete('{{ __('dicms::js_scripts.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.destroy', ['script' => $script->id]) }}')"
                                    class="btn btn-danger btn-sm ms-2"
                                    title="{{ __('dicms::admin.delete') }}"
                                ><i class="fa fa-trash"></i></button>
                            @endcan
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-info">
            {{ __('dicms::js_scripts.scripts.none') }}
        </div>
    @endif
@endsection
