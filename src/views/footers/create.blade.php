@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true])

@section('index_content')
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.store') }}">
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

            <div class="row">
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.create') }}</button>
            </div>
        </form>
@endsection
