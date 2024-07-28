@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            {{__('dicms::sites.new_site')}}
        </h1>
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
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.create') }}</button>
                <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.index') }}">{{ __('dicms::admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
