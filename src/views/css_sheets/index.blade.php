@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center mb-2">
            <span>{{ $site->name }}: {{__('dicms::css_sheets.sheet.title')}}</span>
            <div>
                <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.sheets.create', ['site' => $site->id]) }}" role="button">{{ __('dicms::css_sheets.new') }}</a>
                <a class="btn btn-secondary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" role="button">{{ __('dicms::css_sheets.back') }}</a>
            </div>
        </h1>
        <ul class="list-group">
            @foreach($site->cssSheets as $sheet)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a
                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.sheets.edit', ['site' => $site->id, 'sheet' => $sheet->id]) }}"
                    class=""
                >
                    {{ $sheet->name }}
                </a>
                <button
                    type="button"
                    onclick="confirmDelete('{{ __('dicms::sheets.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.sheets.destroy', ['site' => $site->id, 'sheet' => $sheet->id]) }}')"
                    class="btn btn-danger"
                >{{ __('dicms::admin.delete') }}</button>
            @endforeach
        </ul>
    </div>
@endsection
