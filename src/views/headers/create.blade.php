@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.store') }}">
        @csrf
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('dicms::headers.name') }}</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    aria-describedby="nameHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                />
                <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
                <div id="nameHelp" class="form-text">{{ __('dicms::headers.name.help') }}</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('dicms::headers.description') }}</label>
                <textarea
                    type="text"
                    name="description"
                    id="description"
                    aria-describedby="descriptionHelp"
                    class="form-control"
                >{{ old('description') }}</textarea>
                <div id="descriptionHelp" class="form-text">{{ __('dicms::headers.description.help') }}</div>
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary col mt-2">{{ __('dicms::admin.create') }}</button>
            </div>
        </form>
@endsection
