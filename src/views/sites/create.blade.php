@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true])

@section('index_content')
        @empty($currentSite)
            <div class="alert alert-info">
                {!! __('dicms::errors.empty_site', ['url' => \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.backups.index')]) !!}
            </div>
        @endif
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('dicms::sites.site_name') }}</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    aria-describedby="nameHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
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
                >{{ old('description') }}</textarea>
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
                    value="{{ old('title') }}"
                />
                <div id="titleHelp" class="form-text">{{ __('dicms::sites.site_title_help') }}</div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary col mt-2">{{ __('dicms::admin.create') }}</button>
            </div>
        </form>
@endsection
