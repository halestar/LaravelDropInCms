@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            <span>{{ $site->name }}: {{__('dicms::footers.footer.title')}}</span>
            <div>
                <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.create', ['site' => $site->id]) }}" role="button">{{ __('dicms::footers.new') }}</a>
                <a class="btn btn-secondary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" role="button">{{ __('dicms::footers.back') }}</a>
            </div>
        </h1>
        <ul class="list-group">
            @foreach($site->footers as $footer)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a
                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.edit', ['site' => $site->id, 'footer' => $footer->id]) }}"
                    class=""
                >
                    {{ $footer->name }}
                </a>
                <button
                    type="button"
                    onclick="confirmDelete('{{ __('dicms::footers.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.destroy', ['site' => $site->id, 'footer' => $footer->id]) }}')"
                    class="btn btn-danger"
                >{{ __('dicms::admin.delete') }}</button>
            @endforeach
        </ul>
    </div>
@endsection
