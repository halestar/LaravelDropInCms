@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            {{__('dicms::js_scripts.edit')}}
        </h1>
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.update', ['site' => $site->id, 'script' => $script->id]) }}"  enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('dicms::js_scripts.name') }}</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    aria-describedby="nameHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ $script->name }}"
                />
                <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
                <div id="nameHelp" class="form-text">{{ __('dicms::js_scripts.site_name_help') }}</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('dicms::js_scripts.description') }}</label>
                <textarea
                    type="text"
                    name="description"
                    id="description"
                    aria-describedby="descriptionHelp"
                    class="form-control"
                >{{ $script->description }}</textarea>
                <div id="descriptionHelp" class="form-text">{{ __('dicms::js_scripts.description.help') }}</div>
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
                        @if($script->type == \halestar\LaravelDropInCms\Enums\HeadElementType::Text) checked @endif
                    />
                    <label
                        @if($script->type == \halestar\LaravelDropInCms\Enums\HeadElementType::Text)
                            class="nav-link active btn btn-primary"
                        @else
                            class="nav-link btn btn-primary"
                        @endif
                        for="type_text"
                        data-bs-toggle="tab"
                        data-bs-target="#text-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="text-tab-pane"
                    />
                    {{ __('dicms::js_scripts.text') }}</label>
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
                    >{{ __('dicms::js_scripts.file') }}</label>
                </li>
                <li class="nav-item" role="presentation">
                    <input
                        type="radio"
                        class="btn-check"
                        name="type"
                        id="type_link"
                        value="LINK"
                        autocomplete="off"
                        @if($script->type == \halestar\LaravelDropInCms\Enums\HeadElementType::Link) checked @endif
                    />
                    <label
                        @if($script->type == \halestar\LaravelDropInCms\Enums\HeadElementType::Link)
                            class="nav-link active btn btn-primary"
                        @else
                            class="nav-link btn btn-primary"
                        @endif
                        for="type_link"
                        id="link-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#link-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="link-tab-pane"
                    >{{ __('dicms::js_scripts.link') }}</label>
                </li>
            </ul>
            <div class="tab-content" id="tab-content">
                <div
                    @if($script->type == \halestar\LaravelDropInCms\Enums\HeadElementType::Text)
                        class="tab-pane fade show active"
                    @else
                        class="tab-pane fade"
                    @endif
                    id="text-tab-pane"
                    role="tabpanel"
                    aria-labelledby="text-tab"
                    tabindex="0"
                >
                    <div class="mb-3">
                        <label for="script" class="form-label">{{ __('dicms::js_scripts.content') }}</label>
                        <textarea id="script" name="script" class="form-control" aria-describedby="headernHelp" rows="20">{{ $script->script }}</textarea>
                        <div id="headernHelp" class="form-text">{{ __('dicms::js_scripts.content.help') }}</div>
                    </div>
                </div>

                <div class="tab-pane fade" id="file-tab-pane" role="tabpanel" aria-labelledby="file-tab" tabindex="0">
                    <div class="mb-3">
                        <label for="script_file" class="form-label">{{ __('dicms::js_scripts.file') }}</label>
                        <input class="form-control" aria-describedby="fileHelp" type="file" id="script_file" name="script_file">
                        <div id="fileHelp" class="form-text">{{ __('dicms::js_scripts.content.help') }}</div>
                    </div>
                </div>

                <div
                    @if($script->type == \halestar\LaravelDropInCms\Enums\HeadElementType::Link)
                        class="tab-pane fade pt-3 show active"
                    @else
                        class="tab-pane fade pt-3"
                    @endif
                    id="link-tab-pane"
                    role="tabpanel"
                    aria-labelledby="link-tab"
                    tabindex="2"
                >
                    <div class="mb-3">
                        <label for="href" class="form-label">{{ __('dicms::js_scripts.link.url') }}</label>
                        <input class="form-control" aria-describedby="urlHelp" type="text" id="href" name="href" value="{{ $script->href }}">
                        <div id="urlHelp" class="form-text">{{ __('dicms::js_scripts.link.url.help') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="link_type" class="form-label">{{ __('dicms::js_scripts.link.type') }}</label>
                        <input class="form-control" aria-describedby="urlTypeHelp" type="text" id="link_type" name="link_type" value="{{ $script->link_type }}">
                        <div id="urlTypeHelp" class="form-text">{{ __('dicms::js_scripts.link.type.help') }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.update') }}</button>
                <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}">{{ __('dicms::admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
