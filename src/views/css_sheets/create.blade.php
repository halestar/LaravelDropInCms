@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
    <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sheets.store', ['site' => $site->id]) }}"  enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('dicms::css_sheets.name') }}</label>
            <input
                type="text"
                name="name"
                id="name"
                aria-describedby="nameHelp"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
            />
            <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
            <div id="nameHelp" class="form-text">{{ __('dicms::css_sheets.name.help') }}</div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('dicms::css_sheets.description') }}</label>
            <textarea
                type="text"
                name="description"
                id="description"
                aria-describedby="descriptionHelp"
                class="form-control"
            >{{ old('description') }}</textarea>
            <div id="descriptionHelp" class="form-text">{{ __('dicms::css_sheets.description.help') }}</div>
        </div>

        <ul class="nav nav-tabs" id="content-select" role="tablist">
            <li class="nav-item" role="presentation">
                <input
                    type="radio"
                    class="btn-check"
                    name="type"
                    id="type_text"
                    value="TEXT"
                    autocomplete="off"
                    checked
                />
                <label
                    class="nav-link active btn btn-primary"
                    for="type_text"
                    data-bs-toggle="tab"
                    data-bs-target="#text-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="text-tab-pane"
                    aria-selected="true"
                >{{ __('dicms::css_sheets.text') }}</label>
            </li>
            <li class="nav-item" role="presentation">
                <input
                    type="radio"
                    class="btn-check"
                    name="type"
                    id="type_upload"
                    value="UPLOAD"
                    autocomplete="off"
                />
                <label
                    class="nav-link btn btn-primary"
                    for="type_upload"
                    id="file-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#file-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="file-tab-pane"
                >{{ __('dicms::css_sheets.file') }}</label>
            </li>
            <li class="nav-item" role="presentation">
                <input
                    type="radio"
                    class="btn-check"
                    name="type"
                    id="type_link"
                    value="LINK"
                    autocomplete="off"
                />
                <label
                    class="nav-link btn btn-primary"
                    for="type_link"
                    id="link-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#link-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="link-tab-pane"
                >{{ __('dicms::css_sheets.link') }}</label>
            </li>
        </ul>
        <div class="tab-content" id="tab-content">
            <div class="tab-pane fade show active pt-3" id="text-tab-pane" role="tabpanel" aria-labelledby="text-tab" tabindex="0">
                <div class="mb-3">
                    <label for="sheet" class="form-label">{{ __('dicms::css_sheets.content') }}</label>
                    <textarea id="sheet" name="sheet" class="form-control" aria-describedby="sheetHelp" rows="20">{{ old('sheet') }}</textarea>
                    <div id="sheetHelp" class="form-text">{{ __('dicms::css_sheets.content.help') }}</div>
                </div>
            </div>
            <div class="tab-pane fade pt-3" id="file-tab-pane" role="tabpanel" aria-labelledby="file-tab" tabindex="1">
                <div class="mb-3">
                    <label for="sheet_file" class="form-label">{{ __('dicms::css_sheets.file') }}</label>
                    <input class="form-control" aria-describedby="fileHelp" type="file" id="sheet_file" name="sheet_file">
                    <div id="fileHelp" class="form-text">{{ __('dicms::css_sheets.content.help') }}</div>
                </div>
            </div>
            <div class="tab-pane fade pt-3" id="link-tab-pane" role="tabpanel" aria-labelledby="link-tab" tabindex="2">
                <div class="mb-3">
                    <label for="href" class="form-label">{{ __('dicms::css_sheets.link.url') }}</label>
                    <input class="form-control" aria-describedby="urlHelp" type="text" id="href" name="href" value="{{ old('href') }}">
                    <div id="urlHelp" class="form-text">{{ __('dicms::css_sheets.link.url.help') }}</div>
                </div>
                <div class="mb-3">
                    <label for="link_type" class="form-label">{{ __('dicms::css_sheets.link.type') }}</label>
                    <input class="form-control" aria-describedby="urlTypeHelp" type="text" id="link_type" name="link_type" value="{{ old('link_type') }}">
                    <div id="urlTypeHelp" class="form-text">{{ __('dicms::css_sheets.link.type.help') }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.create') }}</button>
            <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]) }}">{{ __('dicms::admin.cancel') }}</a>
        </div>
    </form>
@endsection
