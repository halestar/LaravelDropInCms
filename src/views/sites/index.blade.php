@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
        <div class="ms-1 row">
            <div class="col-2">{{ __('dicms::admin.name') }}</div>
            <div class="col-5">{{ __('dicms::admin.description') }}</div>
            <div class="col-2">{{ __('dicms::admin.status') }}</div>
        </div>
        <ul class="list-group">
            @foreach(\halestar\LaravelDropInCms\Models\Site::withoutGlobalScope(\halestar\LaravelDropInCms\Models\Scopes\AvailableOnlyScope::class)->get() as $site)
                <li class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-2">{{ $site->name }}</div>
                        <div class="col-5 text-muted small">{{ $site->description }}</div>
                        <div class="col-2">
                            @if($site->archived)
                                <span class="badge text-bg-secondary">{{ __('dicms::admin.archived') }}</span>
                            @elseif($site->active)
                                <span class="badge text-bg-success">{{ __('dicms::admin.active') }}</span>
                            @else
                                <span class="badge text-bg-danger">{{ __('dicms::admin.inactive') }}</span>
                            @endif
                        </div>
                        <div class="col-3 text-end">
                            @can('update', $site)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}"
                                    role="button"
                                    class="btn btn-primary btn-sm"
                                    title="{{ __('dicms::sites.edit_site') }}"
                                ><i class="fa-solid fa-edit"></i></a>
                            @endcan
                            @can('create', \halestar\LaravelDropInCms\Models\Site::class)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.duplicate', ['site' => $site->id]) }}"
                                    role="button"
                                    class="btn btn-warning btn-sm"
                                    title="{{ __('dicms::sites.duplicate') }}"
                                ><i class="fa-solid fa-clone"></i></a>
                            @endcan
                            @can('archive', \halestar\LaravelDropInCms\Models\Site::class)
                            @if(!$site->active)
                            @if($site->archived)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.restore', ['site' => $site->id]) }}"
                                    role="button"
                                    class="btn btn-outline-info btn-sm"
                                    title="{{ __('dicms::sites.restore') }}"
                                ><i class="fa-solid fa-boxes-packing"></i></a>
                            @else
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.archive', ['site' => $site->id]) }}"
                                role="button"
                                class="btn btn-info btn-sm"
                                title="{{ __('dicms::sites.archive') }}"
                            ><i class="fa-solid fa-box-archive"></i></a>
                            @endif
                            @endif
                            @endcan
                            @if(!$site->active)
                            <button
                                type="button"
                                onclick="confirmDelete('{{ __('dicms::sites.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.destroy', ['site' => $site->id]) }}')"
                                class="btn btn-danger btn-sm"
                                title="{{ __('dicms::admin.delete') }}"
                            ><i class="fa fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
@endsection
