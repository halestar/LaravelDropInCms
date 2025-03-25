@extends("dicms::layouts.admin.index", ['template' => $template, ])

@section('index_content')
    @if($site->active)
        <div class="alert alert-warning">
            <strong>{{ __('dicms::admin.warning') }}</strong>{{ __('dicms::errors.active.warning') }}
        </div>
    @endif
    <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.update.settings', ['site' => $site->id]) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('dicms::sites.site_name') }}</label>
            <input
                type="text"
                name="name"
                id="name"
                aria-describedby="nameHelp"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ $site->name }}"
            />
            <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
            <div id="nameHelp" class="form-text">{{ __('dicms::sites.site_name_help') }}</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('dicms::sites.site_description') }}</label>
            <textarea
                type="text"
                name="description"
                id="description"
                aria-describedby="descriptionHelp"
                class="form-control"
            >{{ $site->description }}</textarea>
            <div id="descriptionHelp" class="form-text">{{ __('dicms::sites.site_name_description') }}</div>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">{{ __('dicms::sites.site_title') }}</label>
            <input
                type="text"
                name="title"
                id="title"
                aria-describedby="titleHelp"
                class="form-control"
                value="{{ $site->title }}"
            />
            <div id="titleHelp" class="form-text">{{ __('dicms::sites.site_title_help') }}</div>
        </div>

        <label for="favicon" class="form-label">{{ __('dicms::sites.favicon') }}</label>
        <div class="input-group">
            <label for="favicon" class="input-group-text fw-bolder">&lt;link rel="icon" href=</label>
            <input type="text" name="favicon" id="favicon" value="{{ $site->favicon }}" class="form-control"
                   aria-describedby="faviconHelp" />
            <label for="favicon" class="input-group-text fw-bolder">&gt; </label>
        </div>
        <div id="faviconHelp" class="form-text mb-3">{{ __('dicms::sites.favicon.help') }}</div>

        <label for="body_attr" class="form-label">{{ __('dicms::sites.body.options') }}</label>
        <div class="input-group">
            <label for="body_attr" class="input-group-text fw-bolder">&lt;body </label>
            <input type="text" name="body_attr" id="body_attr" value="{{ $site->body_attr }}" class="form-control" />
            <label for="body_attr" class="input-group-text fw-bolder">&gt; </label>
        </div>
        <div id="faviconHelp" class="form-text mb-3">{{ __('dicms::sites.body.options.help') }}</div>

        <label for="has_wrapper" class="form-label">{{ __('dicms::sites.content.wrapper') }}</label>
        <div class="input-group">
            <div class="input-group-text">
                <input
                    type="checkbox"
                    name="has_wrapper"
                    id="has_wrapper"
                    class="form-check-input mt-0"
                    value="1"
                    @if($site->tag) checked @endif
                />
            </div>
            <label for="tag" class="input-group-text">&lt;</label>
            <select name="tag" id="tag" class="form-select">
                @foreach(\halestar\LaravelDropInCms\Enums\WrapperTagType::array() as $value => $name)
                    <option value="{{ $value }}" @if($site->tag && $value == $site->tag->value) selected @endif >{{ $name }}</option>
                @endforeach
            </select>
            <input
                type="text"
                name="options"
                id="options"
                aria-describedby="optionsHelp"
                class="form-control"
                value="{{ $site->options }}"
            />
            <label for="options" class="input-group-text">&gt;</label>
        </div>
        <div id="optionsHelp" class="form-text mb-3">{{ __('dicms::sites.content.wrapper.help') }}</div>


        <div class="row mt-3">
            <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.update') }}</button>
        </div>
    </form>
@endsection
