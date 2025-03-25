@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true, 'currentSite' => $site])

@section('index_content')
    @if($site->headers->count() > 0)
    <div class="ms-1 row">
        <div class="col-2">{{ __('dicms::admin.name') }}</div>
        <div class="col-9">{{ __('dicms::admin.description') }}</div>
    </div>
    <ul class="list-group">
        @foreach($site->headers as $header)
            <li class="list-group-item list-group-item-action">
                <div class="row align-items-center">
                    <div class="col-2">{{ $header->name }}</div>
                    <div class="col-8 text-muted small">{{ $header->description }}</div>
                    <div class="col-2 text-end">
                        @can('create', \halestar\LaravelDropInCms\Models\Header::class)
                        <a
                            role="button"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.edit', ['header' => $header->id, 'site' => $site->id]) }}"
                            class="btn btn-primary btn-sm ms-2"
                            title="{{ __('dicms::admin.edit') }}"
                        ><i class="fa fa-edit"></i></a>
                        @endcan
                        @can('create', \halestar\LaravelDropInCms\Models\Header::class)
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.duplicate', ['header' => $header->id, 'site' => $site->id]) }}"
                                role="button"
                                class="btn btn-warning btn-sm ms-2"
                                title="{{ __('dicms::headers.duplicate') }}"
                            ><i class="fa-solid fa-clone"></i></a>
                        @endcan
                        @can('delete', $header)
                        <button
                            type="button"
                            onclick="confirmDelete('{{ __('dicms::headers.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.destroy', ['header' => $header->id, 'site' => $site->id]) }}')"
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
            {{ __('dicms::headers.header.none') }}
        </div>
    @endif
@endsection
