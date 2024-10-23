@extends("dicms::layouts.admin.index", ['template' => $template, 'objEditable' => $objEditable])

@section('index_content')
        <div class="row border-end border-start border-bottom rounded-bottom p-1 collapse advanced_options" id="advanced_options">
            <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.update', ['footer' => $footer->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('dicms::footers.name') }}</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        aria-describedby="nameHelp"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ $footer->name }}"
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
                    >{{ $footer->description }}</textarea>
                    <div id="descriptionHelp" class="form-text">{{ __('dicms::footers.description.help') }}</div>
                </div>

                <div class="row py-2">
                    <button type="submit" class="btn btn-primary col">{{ __('dicms::footers.settings.advanced.update') }}</button>
                </div>
            </form>
        </div>
        <div class="row mt-0 justify-content-center">
            <div class="col col-auto border-end border-bottom border-start rounded-bottom p-2">
                <a href="#" data-bs-toggle="collapse" data-bs-target=".advanced_options">
                    <i class="fa-solid fa-angles-down advanced_options collapse show" ></i>
                    <i class="fa-solid fa-angles-up advanced_options collapse" ></i>
                    {{ __('dicms::pages.settings.advanced') }}
                </a>
            </div>
        </div>

        <div class="mb-3">
            <label for="footer" class="form-label">{{ __('dicms::footers.content') }}</label>
            <div id="footerHelp" class="form-text border-bottom">{{ __('dicms::footers.content.help') }}</div>
            <div id="grapes-js-editor">
                <div style="padding: 15px; z-index: 9999;" data-gjs-type="editable">
                    {{ $footer->html }}
                </div>
            </div>
        </div>

        <form action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.update.content', ['footer' => $footer->id]) }}" method="POST" id="update_content">
            @csrf
            @method('PUT')
            <div class="row">
                <input type="hidden" name="footer" id="footer" />
                <input type="hidden" name="data" id="data" />
                <input type="hidden" name="css" id="css" />
                <button type="button" class="btn btn-primary col m-2" onclick="update();">{{ __('dicms::admin.update') }}</button>
            </div>
        </form>
@endsection
@push('scripts')
    <script>
        function update()
        {
            $('#footer').val(editor.getHtml());
            $('#css').val(editor.getCss());
            $('#data').val(JSON.stringify(editor.getProjectData()));
            $('form#update_content').submit();
        }
    </script>
@endpush
