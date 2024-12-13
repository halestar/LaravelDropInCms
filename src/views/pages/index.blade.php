@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true])

@section('index_content')
    @if(\halestar\LaravelDropInCms\Models\Page::count() > 0)
        <div class="ms-1 row">
            <div class="col-2">{{ __('dicms::admin.name') }}</div>
            <div class="col-6">{{ __('dicms::admin.url') }}</div>
            <div class="col-2">{{ __('dicms::admin.status') }}</div>
        </div>
        <ul class="list-group">
            @foreach(\halestar\LaravelDropInCms\Models\Page::normal()->get() as $page)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div class="col-2">{{ $page->name }}</div>
                    <div class="col-6 text-muted small">{{ \halestar\LaravelDropInCms\DiCMS::dicmsPublicRoute() . "/" . $page->url }}</div>
                    <div class="col-2 ps-4">
                        @if($page->published)
                            <span class="badge text-bg-success">{{ __('dicms::admin.published') }}</span>
                        @else
                            <span class="badge text-bg-danger">{{ __('dicms::admin.unpublished') }}</span>
                        @endif
                    </div>
                    <div class="col-2 text-end">
                        @can('update', $page)
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]) }}"
                                role="button"
                                class="btn btn-primary btn-sm"
                                title="{{ __('dicms::pages.edit') }}"
                            ><i class="fa-solid fa-edit"></i></a>
                        @endcan
                        @can('create', \halestar\LaravelDropInCms\Models\Page::class)
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.dupe', ['page' => $page->id]) }}"
                                role="button"
                                class="btn btn-warning btn-sm"
                                title="{{ __('dicms::pages.duplicate') }}"
                            ><i class="fa-solid fa-clone"></i></a>
                        @endcan
                        @if(!$page->published)

                            <button
                                type="button"
                                onclick="confirmDelete('{{ __('dicms::pages.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.destroy', ['page' => $page->id]) }}')"
                                class="btn btn-danger btn-sm"
                                title="{{ __('dicms::admin.delete') }}"
                            ><i class="fa fa-trash"></i></button>
                        @endif
                    </div>
            @endforeach
        </ul>
    @else
        <div class="alert alert-info">
            {{ __('dicms::pages.pages.none') }}
        </div>
    @endif
@endsection
