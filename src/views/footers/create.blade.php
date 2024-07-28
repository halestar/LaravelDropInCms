@extends("dicms::layouts.admin", ['include_editor' => true])

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            {{__('dicms::footers.new')}}
        </h1>
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.store', ['site' => $site->id]) }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('dicms::footers.name') }}</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    aria-describedby="nameHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                />
                <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
                <div id="nameHelp" class="form-text">{{ __('dicms::footers.name.help') }}</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('dicms::footers.description') }}</label>
                <textarea
                    type="text"
                    name="description"
                    id="description"
                    aria-describedby="descriptionHelp"
                    class="form-control"
                >{{ old('description') }}</textarea>
                <div id="descriptionHelp" class="form-text">{{ __('dicms::footers.description.help') }}</div>
            </div>

            <div class="mb-3">
                <label for="options" class="form-label">{{ __('dicms::footers.options') }}</label>
                <input
                    type="text"
                    name="options"
                    id="options"
                    aria-describedby="optionsHelp"
                    class="form-control"
                    value="{{ old('options') }}"
                />
                <div id="optionsHelp" class="form-text">{{ __('dicms::footers.options.help') }}</div>
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.create') }}</button>
                <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.index', ['site' => $site->id]) }}">{{ __('dicms::admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
