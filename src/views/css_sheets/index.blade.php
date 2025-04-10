@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true, 'currentSite' => $site])

@section('index_content')
    @if($site->siteCss->count() > 0)
    <div class="ms-1 row">
        <div class="col-2">{{ __('dicms::admin.name') }}</div>
        <div class="col-9">{{ __('dicms::admin.description') }}</div>
    </div>
    <ul class="list-group">
        @foreach($site->siteCss as $sheet)
            <li class="list-group-item list-group-item-action">
                <div class="row align-items-center">
                    <div class="col-2">{{ $sheet->name }}</div>
                    <div class="col-8 text-muted small">{{ $sheet->description }}</div>
                    <div class="col-2 text-end">
                        @can('update', $sheet)
                        <a
                            role="button"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sheets.edit', ['site' => $site->id, 'sheet' => $sheet->id]) }}"
                            class="btn btn-primary btn-sm ms-2"
                            title="{{ __('dicms::admin.edit') }}"
                        ><i class="fa fa-edit"></i></a>
                        @endcan
                        @can('create', \halestar\LaravelDropInCms\Models\CssSheet::class)
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sheets.duplicate', ['site' => $site->id, 'sheet' => $sheet->id]) }}"
                                role="button"
                                class="btn btn-warning btn-sm ms-2"
                                title="{{ __('dicms::css_sheets.duplicate') }}"
                            ><i class="fa-solid fa-clone"></i></a>
                        @endcan
                        @can('delete', $sheet)
                        <button
                            type="button"
                            onclick="confirmDelete('{{ __('dicms::css_sheets.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sheets.destroy', ['site' => $site->id, 'sheet' => $sheet->id]) }}')"
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
            {{ __('dicms::css_sheets.sheet.none') }}
        </div>
    @endif
@endsection
