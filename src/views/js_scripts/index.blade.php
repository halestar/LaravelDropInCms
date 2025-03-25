@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true, 'currentSite' => $site])

@section('index_content')
    @if($site->siteJs->count() > 0)
        <div class="ms-1 row">
            <div class="col-2">{{ __('dicms::admin.name') }}</div>
            <div class="col-9">{{ __('dicms::admin.description') }}</div>
        </div>
        <ul class="list-group">
            @foreach($site->siteJs as $script)
                <li class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-2">{{ $script->name }}</div>
                        <div class="col-8 text-muted small">{{ $script->description }}</div>
                        <div class="col-2 text-end">
                            @can('update', $script)
                                <a
                                    role="button"
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.edit', ['site' => $site->id, 'script' => $script->id]) }}"
                                    class="btn btn-primary btn-sm ms-2"
                                    title="{{ __('dicms::admin.edit') }}"
                                ><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('create', \halestar\LaravelDropInCms\Models\JsScript::class)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.duplicate', ['site' => $site->id, 'script' => $script->id]) }}"
                                    role="button"
                                    class="btn btn-warning btn-sm ms-2"
                                    title="{{ __('dicms::js_scripts.duplicate') }}"
                                ><i class="fa-solid fa-clone"></i></a>
                            @endcan
                            @can('delete', $script)
                                <button
                                    type="button"
                                    onclick="confirmDelete('{{ __('dicms::js_scripts.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.destroy', ['site' => $site->id, 'script' => $script->id]) }}')"
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
