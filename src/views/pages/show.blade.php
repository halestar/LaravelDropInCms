@extends("dicms::layouts.admin", ['include_editor' => $editor])

@section('content')
    <div class="container">

        <h1 class="border-bottom pb-2 d-flex justify-content-between align-items-center">
            <span>{{ $site->name }} : {{ $page->name }}</span>
            <div>
                <a
                    class="btn btn-info"
                    href="#"
                >{{ __('dicms::pages.preview') }}</a>
                @can('update', $page)
                    <a
                        class="btn btn-primary"
                        href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.edit.settings', ['page' => $page->id]) }}"
                    >{{ __('dicms::pages.settings.edit') }}</a>
                @endcan
                @can('delete', $page)
                    <a
                        class="btn btn-danger"
                        href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.destroy', ['site' => $site->id, 'page' => $page->id]) }}"
                    >{{ __('dicms::pages.delete') }}</a>
                @endcan
            </div>
        </h1>

        @if($page->published)
            <div class="alert alert-warning">
                <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.published.warning') }}
            </div>
        @endif
        <div class="row border-end border-start border-bottom rounded-bottom p-1 collapse" id="advanced_options">
            <div class="col-md">
                <livewire:css-sheet-manager :siteId="$page->site_id" :container="$page" />
                <livewire:js-script-manager :siteId="$page->site_id" :container="$page" />


            </div>
            <div class="col-md">
                <form
                    action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.update', ['site' => $site->id, 'page' => $page->id]) }}"
                    method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="input-group" aria-describedby="titleHelp">
                        <label for="title" class="input-group-text">{{ __('dicms::pages.title') }}</label>
                        <input type="text" name="title" id="title" value="{{ $page->title }}" class="form-control"
                               @cannot('update', $page) disabled @endcan placeholder="{{ $site->title }}"/>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $page) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                    </div>
                    <div id="titleHelp" class="form-text mb-3">{{ __('dicms::pages.title.help') }}</div>

                    <div class="input-group" aria-describedby="headerHelp">
                        <label for="header_id"
                               class="input-group-text">{{ __('dicms::pages.override.headers') }}</label>
                        <select name="header_id" id="header_id" class="form-select"
                                @cannot('update', $page) disabled @endcan>
                            <option value="">{{ __('dicms::pages.override.from') }}
                                : {{ $site->defaultHeader()->exists()? $site->defaultHeader->name: __('admin.none') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Header::all() as $header)
                                <option value="{{ $header->id }}"
                                        @if($header->id == $page->header_id) selected @endif>{{ $header->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $page) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Header::class)
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.headers.index', ['site' => $site->id]) }}"
                                class="btn btn-outline-secondary"
                            >{{ __('dicms::admin.manage_headers') }}</a>
                        @endcan
                    </div>
                    <div id="headerHelp"
                         class="form-text mb-3">{{ __('dicms::sites.select_default_header_help') }}</div>

                    <div class="input-group" aria-describedby="footerHelp">
                        <label for="footer_id"
                               class="input-group-text">{{ __('dicms::pages.override.footers') }}</label>
                        <select name="footer_id" id="footer_id" class="form-select"
                                @cannot('update', $page) disabled @endcan>
                            <option value="">{{ __('dicms::pages.override.from') }}
                                : {{ $site->defaultFooter()->exists()? $site->defaultFooter->name: __('admin.none') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Footer::all() as $footer)
                                <option value="{{ $footer->id }}"
                                        @if($footer->id == $page->footer_id) selected @endif>{{ $footer->name }}</option>
                            @endforeach
                        </select>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Footer::class)
                            <button type="submit" class="btn btn-outline-primary"
                                    @cannot('update', $page) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_footers') }}</a>
                        @endcan
                    </div>
                    <div id="footerHelp"
                         class="form-text mb-3">{{ __('dicms::sites.select_default_footer_help') }}</div>

                </form>
            </div>
        </div>
        <div class="row mt-0 justify-content-center">
            <div class="col col-auto border-end border-bottom border-start rounded-bottom p-2">
                <button type="button" data-bs-toggle="collapse" data-bs-target="#advanced_options"
                        class="btn btn-primary">{{ __('dicms::headers.settings.advanced') }}</button>
            </div>
        </div>

        @can('publish', $page)
            @if($page->published)
                <div class="alert alert-danger mt-3">
                    <strong>{{ __('dicms::admin.danger') }}</strong> {{ __('dicms::errors.published.danger.unpublish') }}
                </div>
                <div class="row">
                    <a
                        href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.unpublish', ['page' => $page->id]) }}"
                        role="button"
                        class="btn btn-danger col"
                    >{{ __('dicms::pages.unpublish') }}</a>
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.published.warning.publish') }}
                </div>
                <div class="row">
                    <a
                        role="button"
                        href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.publish', ['page' => $page->id]) }}"
                        class="btn btn-warning col"
                    >{{ __('dicms::pages.publish') }}</a>
                </div>
            @endif
        @endcan

        <h2 class="border-bottom py-2 d-flex justify-content-between align-items-center">
            {{ __('dicms::pages.pages.web') }}
        </h2>
        <div id="page_editor">
            <div style="padding: 15px; z-index: 9999;" data-gjs-type="editable">
            </div>
        </div>
        <form action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.update.content', ['page' => $page->id]) }}" method="POST" id="update_content">
            @csrf
            @method('PUT')
            <div class="row">
                <input type="hidden" name="page" id="page" />
                <input type="hidden" name="data" id="data" />
                <input type="hidden" name="css" id="css" />
                <button type="button" class="btn btn-primary col m-2" onclick="update();">{{ __('dicms::admin.update') }}</button>
                <a class="btn btn-secondary col m-2" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.index', ['site' => $site->id]) }}">{{ __('dicms::admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function update()
        {
            $('#page').val(editor.getHtml());
            $('#css').val(editor.getCss());
            $('#data').val(JSON.stringify(editor.getProjectData()));
            $('form#update_content').submit();
        }
    </script>
@endpush
