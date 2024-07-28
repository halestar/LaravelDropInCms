@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            {{__('dicms::menus.new')}}
        </h1>
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.store', ['site' => $site->id]) }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('dicms::menus.name') }}</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    aria-describedby="nameHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                />
                <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
                <div id="nameHelp" class="form-text">{{ __('dicms::menus.name.help') }}</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('dicms::menus.description') }}</label>
                <textarea
                    type="text"
                    name="description"
                    id="description"
                    aria-describedby="descriptionHelp"
                    class="form-control"
                >{{ old('description') }}</textarea>
                <div id="descriptionHelp" class="form-text">{{ __('dicms::menus.description.help') }}</div>
            </div>


            <div class="row">
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.create') }}</button>
                <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.index', ['site' => $site->id]) }}">{{ __('dicms::admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
