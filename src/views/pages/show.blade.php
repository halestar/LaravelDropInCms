@extends("dicms::layouts.admin.index", ['template' => $template, 'objEditable' => $objEditable, 'currentSite' => $site])

@section('index_content')

        @if($page->published)
            <div class="alert alert-warning">
                <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.published.warning') }}
            </div>
        @endif
        <form
            action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.update', ['page' => $page->id]) }}"
            method="POST"
        >

            @csrf
            @method('PATCH')
            <div class="row border-end border-start border-bottom rounded-bottom p-1 collapse advanced_options">
                <div class="col-md">
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="override_css"
                                id="override_css_0"
                                value="0"
                                @if(!$page->override_css) checked @endif
                            />
                            <label class="form-check-label" for="override_css_0">{{ __('dicms::pages.css.include') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="override_css"
                                id="override_css_1"
                                value="1"
                                @if($page->override_css) checked @endif
                            >
                            <label class="form-check-label" for="override_css_1">{{ __('dicms::pages.css.exclude') }}</label>
                        </div>
                    </div>
                    <div id="page_css_manager" class="mb-5">
                        <livewire:css-sheet-manager :container="$page" :title="__('dicms::pages.css.include.page')" />
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="override_js"
                                id="override_js_0"
                                value="0"
                                @if(!$page->override_js) checked @endif
                            />
                            <label class="form-check-label" for="override_js_0">{{ __('dicms::pages.js.include') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="override_js"
                                id="override_js_1"
                                value="1"
                                @if($page->override_js) checked @endif
                            />
                            <label class="form-check-label" for="page_js_page">{{ __('dicms::pages.js.exclude') }}</label>
                        </div>
                    </div>
                    <div id="page_js_manager">
                        <livewire:js-script-manager :container="$page" :title="__('dicms::pages.js.include.page')" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="input-group" aria-describedby="titleHelp">
                        <label for="title" class="input-group-text">{{ __('dicms::pages.title') }}</label>
                        <input type="text" name="title" id="title" value="{{ $page->title }}" class="form-control"
                               @cannot('update', $page) disabled @endcan placeholder="{{ $page->Title() }}"/>
                    </div>
                    <div id="titleHelp" class="form-text mb-3">{{ __('dicms::pages.title.help') }}</div>

                    <div class="mb-3">
                        <h3 class="border-bottom">{{ __('dicms::pages.header') }}</h3>
                        <div class="input-group" aria-describedby="headerHelp">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="override_header"
                                    id="override_header_0"
                                    value="0"
                                    @if(!$page->override_header) checked @endif
                                />
                            </div>
                            <label class="input-group-text" for="override_header_0">{{ __('dicms::pages.headers.use') }}</label>
                        </div>
                        @if($site)
                        <div class="input-group" aria-describedby="headerHelp">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0"
                                    type="radio"
                                    name="override_header"
                                    id="override_header_1"
                                    value="1"
                                    @if($page->override_header) checked @endif
                                />
                            </div>
                            <label for="override_header_1"
                                   class="input-group-text">{{ __('dicms::pages.override.headers') }}</label>
                            <select name="header_id" id="header_id" class="form-select"
                                    @cannot('update', $page) disabled @endcan>
                                <option value="">{{ __('dicms::pages.header.no') }}</option>
                                @foreach($site->headers as $header)
                                    <option value="{{ $header->id }}"
                                            @if($header->id == $page->header_id) selected @endif>{{ $header->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div id="headerHelp"
                             class="form-text mb-3">{{ __('dicms::sites.select_default_header_help') }}</div>
                    </div>

                    <div class="mb-3">
                        <h3 class="border-bottom">{{ __('dicms::pages.footer') }}</h3>
                        <div class="input-group" aria-describedby="headerHelp">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="override_footer"
                                    id="override_footer_0"
                                    value="0"
                                    @if(!$page->override_footer) checked @endif
                                />
                            </div>
                            <label class="input-group-text" for="override_footer_0">{{ __('dicms::pages.footer.use') }}</label>
                        </div>
                        @if($site)
                        <div class="input-group" aria-describedby="footerHelp">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0"
                                    type="radio"
                                    name="override_footer"
                                    id="override_footer_1"
                                    value="1"
                                    @if($page->override_footer) checked @endif
                                />
                            </div>
                            <label for="override_footer_1"
                                   class="input-group-text">{{ __('dicms::pages.override.footers') }}</label>
                            <select name="footer_id" id="footer_id" class="form-select"
                                    @cannot('update', $page) disabled @endcan>
                                <option value="">{{ __('dicms::pages.footer.no') }}</option>
                                @foreach($site->footers as $footer)
                                    <option value="{{ $footer->id }}"
                                            @if($footer->id == $page->footer_id) selected @endif>{{ $footer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div id="footerHelp"
                             class="form-text mb-3">{{ __('dicms::sites.select_default_footer_help') }}</div>
                    </div>
                </div>

                <div class="row py-2">
                    <button type="submit" class="btn btn-primary col">{{ __('dicms::headers.settings.advanced.update') }}</button>
                </div>

            </div>

        </form>
        <div class="row mt-0 justify-content-center">
            <div class="col col-auto border-end border-bottom border-start rounded-bottom p-2">
                <a href="#" data-bs-toggle="collapse" data-bs-target=".advanced_options">
                    <i class="fa-solid fa-angles-down advanced_options collapse show" ></i>
                    <i class="fa-solid fa-angles-up advanced_options collapse" ></i>
                    {{ __('dicms::pages.settings.advanced') }}
                </a>
            </div>
        </div>

        @if(!$page->plugin_page)
            @can('publish', $page)
                @if($page->published)
                    <div class="alert alert-danger mt-3">
                        <strong>{{ __('dicms::admin.danger') }}</strong> {{ __('dicms::errors.published.danger.unpublish') }}
                    </div>
                    <div class="row mb-5">
                        <a
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.unpublish', ['site' => $site->id, 'page' => $page->id]) }}"
                            role="button"
                            class="btn btn-danger col"
                        >{{ __('dicms::pages.unpublish') }}</a>
                    </div>
                @else
                    <div class="alert alert-warning mt-3">
                        <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.published.warning.publish') }}
                    </div>
                    <div class="row mb-5">
                        <a
                            role="button"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.publish', ['site' => $site->id, 'page' => $page->id]) }}"
                            class="btn btn-warning col"
                        >{{ __('dicms::pages.publish') }}</a>
                    </div>
                @endif
            @endcan
        @endif

        <x-dicms::web-editor :editableObj="$page" :title="__('dicms::pages.content')" :help="__('dicms::pages.content.help')" />
@endsection
