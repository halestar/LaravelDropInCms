@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom pb-2 d-flex justify-content-between align-items-center">
            <span>{{ $site->name }} {{ __('dicms::sites.site.settings') }}</span>
        </h1>
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

            <div class="mb-3">
                <label for="body_styles" class="form-label">{{ __('dicms::sites.body_styles') }}</label>
                <input
                    type="text"
                    name="body_styles"
                    id="body_styles"
                    aria-describedby="body_stylesHelp"
                    class="form-control"
                    value="{{ $site->body_styles }}"
                />
                <div id="body_stylesHelp" class="form-text">{{ __('dicms::sites.body_styles.help') }}</div>
            </div>

            <div class="mb-3">
                <label for="body_classes" class="form-label">{{ __('dicms::sites.body_classes') }}</label>
                <input
                    type="text"
                    name="body_classes"
                    id="body_classes"
                    aria-describedby="body_classesHelp"
                    class="form-control"
                    value="{{ $site->body_classes }}"
                />
                <div id="body_classesHelp" class="form-text">{{ __('dicms::sites.body_classes.help') }}</div>
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.update') }}</button>
                <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}">{{ __('dicms::admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
