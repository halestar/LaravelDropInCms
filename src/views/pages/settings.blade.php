@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true])

@section('index_content')
        <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.update.settings', ['page' => $page->id]) }}"  enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('dicms::pages.name') }}</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    aria-describedby="nameHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ $page->name }}"
                />
                <x-error-display key="name">{{ $errors->first('name') }}</x-error-display>
                <div id="nameHelp" class="form-text">{{ __('dicms::pages.name.help') }}</div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">{{ __('dicms::pages.title') }}</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    aria-describedby="titleHelp"
                    class="form-control"
                    value="{{ $page->title }}"
                />
                <div id="titleHelp" class="form-text">{{ __('dicms::pages.title.help') }}</div>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">{{ __('dicms::pages.slug') }}</label>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    aria-describedby="slugHelp"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ $page->slug }}"
                    onkeyup="cleanSlug()"
                    onchange="cleanSlug()"
                />
                <x-error-display key="slug">{{ $errors->first('slug') }}</x-error-display>
                <div id="slugHelp" class="form-text">{{ __('dicms::pages.slug.help') }}</div>
            </div>

            <div class="mb-3">
                <label for="path" class="form-label">{{ __('dicms::pages.path') }}</label>
                <input
                    type="text"
                    name="path"
                    id="path"
                    aria-describedby="pathHelp"
                    class="form-control"
                    value="{{ $page->path }}"
                    onkeyup="cleanPath()"
                    onchange="cleanPath()"
                />
                <div id="slugHelp" class="form-text">{{ __('dicms::pages.slug.help') }}</div>
            </div>

            <div class="row justify-content-center">
                <div class="col col-auto">
                    <h5 class="alert-heading">{{ __('dicms::pages.url') }}</h5>
                    <div class="row">
                        <div class="col col-auto text-end pe-0 me-0">
                            <div
                                class="bg-dark-subtle ps-2 rounded-start border-bottom border-start border-top border-dark py-2 h3 fw-bold">{{ \halestar\LaravelDropInCms\DiCMS::dicmsPublicRoute() }}
                                /
                            </div>
                            <div class="form-text mx-3 text-center">{{ __('dicms::pages.front_url') }}</div>
                        </div>
                        <div class="col col-auto text-center px-0 mx-0 d-none" id="path_display_container">
                            <div class="bg-secondary-subtle border-bottom border-top border-secondary py-2 h3 fw-bold"
                                 id="path_display">&nbsp;
                            </div>
                            <div class="form-text mx-3 text-center">{{ __('dicms::pages.path') }}</div>
                        </div>
                        <div class="col col-auto text-start ps-0 ms-0 d-none" id="slug_display_container">
                            <div
                                class="bg-primary-subtle rounded-end border-bottom border-top border-end border-primary pe-2 py-2 h3 fw-bold"
                                id="slug_display">&nbsp;
                            </div>
                            <div class="form-text mx-3 text-center">{{ __('dicms::pages.slug') }}</div>
                        </div>
                    </div>
                    @error('url')
                    <div class="alert alert-danger">{{ $errors->first('url') }}</div>
                    @enderror
                </div>
            </div>


            <div class="row">
                <button type="submit" class="btn btn-primary col m-2">{{ __('dicms::admin.update') }}</button>
            </div>
        </form>
@endsection
@push('scripts')
    <script>
        function updateSlugDisplay()
        {
            let slug = $('#slug').val();
            if(slug) {
                $('#slug_display_container').removeClass('d-none');
                $('#slug_display').html(slug);
            }
            else
            {
                $('#slug_display_container').addClass('d-none');
                $('#slug_display').html('&nbsp;');
            }
        }

        function cleanSlug(event)
        {
            $('#slug').val($('#slug').val().replace(/[^a-zA-Z0-9_-]/g, '-').toLowerCase());
            updateSlugDisplay();
        }

        function updatePathDisplay()
        {
            let path = $('#path').val();
            if(path) {
                $('#path_display_container').removeClass('d-none');
                $('#path_display').html(path + '/');
            }
            else
            {
                $('#path_display_container').addClass('d-none');
                $('#path_display').html('&nbsp;');
            }
        }

        function cleanPath(event)
        {
            $('#path').val($('#path').val().replace(/[^a-zA-Z0-9/_-]/g, '-').toLowerCase());
            updatePathDisplay();
        }
        updateSlugDisplay();
        updatePathDisplay();

    </script>
@endpush
