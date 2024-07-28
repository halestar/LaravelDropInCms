@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            <span>{{ $site->name }}: {{__('dicms::content_headers.headers_title')}}</span>
            <div>
                <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.content_headers.create', ['site' => $site->id]) }}" role="button">{{ __('dicms::content_headers.new') }}</a>
                <a class="btn btn-secondary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" role="button">{{ __('dicms::content_headers.back') }}</a>
            </div>
        </h1>
        <ul class="list-group">
            @foreach($site->contentHeaders as $header)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a
                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.content_headers.edit', ['site' => $site->id, 'content_header' => $header->id]) }}"
                    class=""
                >
                    {{ $header->name }}
                </a>
                <button
                    type="button"
                    onclick="confirmDelete('{{ __('dicms::content_headers.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.content_headers.destroy', ['site' => $site->id, 'content_header' => $header->id]) }}')"
                    class="btn btn-danger"
                >{{ __('dicms::admin.delete') }}</button>
            @endforeach
        </ul>
    </div>
@endsection
