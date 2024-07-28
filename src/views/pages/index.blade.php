@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center mb-2">
            <span>{{ $site->name }}: {{__('dicms::pages.pages.title')}}</span>
            <div>
                <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.create', ['site' => $site->id]) }}" role="button">{{ __('dicms::pages.new') }}</a>
                <a class="btn btn-secondary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" role="button">{{ __('dicms::pages.back') }}</a>
            </div>
        </h1>
        <ul class="list-group">
            @foreach($site->pages as $page)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a
                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $site->id, 'page' => $page->id]) }}"
                    class=""
                >
                    {{ $page->name }}
                </a>
                <span>{{ $page->url }}</span>
                <button
                    type="button"
                    onclick="confirmDelete('{{ __('dicms::pages.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.destroy', ['site' => $site->id, 'page' => $page->id]) }}')"
                    class="btn btn-danger"
                >{{ __('dicms::admin.delete') }}</button>
            @endforeach
        </ul>
    </div>
@endsection
