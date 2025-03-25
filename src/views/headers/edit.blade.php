@extends("dicms::layouts.admin.index", ['template' => $template, 'objEditable' => $objEditable, 'currentSite' => $site])

@section('index_content')
            <div class="row border-end border-start border-bottom rounded-bottom p-1 collapse advanced_options" id="advanced_options">
                <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.update', ['header' => $header->id, 'site' => $site->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('dicms::headers.name') }}</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            aria-describedby="nameHelp"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ $header->name }}"
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
                        >{{ $header->description }}</textarea>
                        <div id="descriptionHelp" class="form-text">{{ __('dicms::headers.description.help') }}</div>
                    </div>

                    <div class="row py-2">
                        <button type="submit" class="btn btn-primary col">{{ __('dicms::headers.settings.advanced.update') }}</button>
                    </div>
                </form>
            </div>
            <div class="row mt-0 justify-content-center">
                <div class="col col-auto border-end border-bottom border-start rounded-bottom">
                    <a href="#" data-bs-toggle="collapse" data-bs-target=".advanced_options">
                        <i class="fa-solid fa-angles-down advanced_options collapse show" ></i>
                        <i class="fa-solid fa-angles-up advanced_options collapse" ></i>
                        {{ __('dicms::pages.settings.advanced') }}
                    </a>
                </div>
            </div>
            <x-dicms::web-editor :editableObj="$header" :title="__('dicms::headers.content')" :help="__('dicms::headers.content.help')" />
@endsection
