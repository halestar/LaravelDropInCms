@extends("dicms::layouts.admin")

@section('content')
    <div class="container">

        <h1 class="border-bottom pb-2 d-flex justify-content-between align-items-center">
            <span>{{ $site->name }} {{ __('dicms::sites.site') }}</span>
            <div>
                <a
                        class="btn btn-info"
                        href="#"
                >{{ __('dicms::sites.preview_site') }}</a>
                @can('update', $site)
                    <a
                            class="btn btn-primary"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.edit', ['site' => $site->id]) }}"
                    >{{ __('dicms::sites.edit_site') }}</a>
                @endcan
                @can('delete', $site)
                    <a
                            class="btn btn-danger"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.destroy', ['site' => $site->id]) }}"
                    >{{ __('dicms::sites.delete_site') }}</a>
                @endcan
            </div>
        </h1>

        @if($site->active)
            <div class="alert alert-warning">
                <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.active.warning') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md">
                <h4 class="border-bottom">{{ __('dicms::sites.sheets') }}</h4>
                <form action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.css.add', ['site' => $site->id]) }}"
                      method="POST">
                    @csrf
                    <div class="input-group" aria-describedby="cssSheetHelp">
                        <label for="sheet_id" class="input-group-text">{{ __('dicms::sites.sheets.add') }}</label>
                        <select name="sheet_id" id="sheet_id" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.sheets.add.select') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\CssSheet::whereNotIn('id', $site->siteCss->pluck('id'))->get() as $sheet)
                                <option value="{{ $sheet->id }}">{{ $sheet->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.add') }}</button>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\CssSheet::class)
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.sheets.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_css_sheets') }}</a>
                        @endcan
                    </div>
                </form>
                <div id="cssSheetHelp" class="form-text mb-3">{{ __('dicms::sites.sheets.add.help') }}</div>
                @if($site->siteCss()->count() > 0)
                    <h5 class="border-bottom">{{ __('dicms::sites.sheets.assigned') }}</h5>
                    <ul class="list-group">
                        @foreach($site->siteCss as $css)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                css_id="{{ $css->id }}">
                                {{ $css->name }}
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.css.remove', ['site' => $site->id, 'cssSheet' => $css->id]) }}"
                                    class="btn btn-danger"
                                    role="button"
                                >{{ __('dicms::admin.remove') }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <h4 class="border-bottom mt-5">{{ __('dicms::sites.scripts') }}</h4>
                <form action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.js.add', ['site' => $site->id]) }}"
                      method="POST">
                    @csrf
                    <div class="input-group" aria-describedby="jsScriptHelp">
                        <label for="script_id" class="input-group-text">{{ __('dicms::sites.scripts.add') }}</label>
                        <select name="script_id" id="script_id" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.scripts.add.select') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\JsScript::whereNotIn('id', $site->siteJs->pluck('id'))->get() as $script)
                                <option value="{{ $script->id }}">{{ $script->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.add') }}</button>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\JsScript::class)
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_js_scripts') }}</a>
                        @endcan
                    </div>
                </form>
                <div id="jsScriptHelp" class="form-text mb-3">{{ __('dicms::sites.scripts.add.help') }}</div>
                @if($site->siteJs()->count() > 0)
                    <h5 class="border-bottom">{{ __('dicms::sites.scripts.assigned') }}</h5>
                    <ul class="list-group">
                        @foreach($site->siteJs as $js)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                js_id="{{ $js->id }}">
                                {{ $js->name }}
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.js.remove', ['site' => $site->id, 'jsScript' => $js->id]) }}"
                                    class="btn btn-danger"
                                    role="button"
                                >{{ __('dicms::admin.remove') }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-md">
                <form action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.update', ['site' => $site->id]) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="input-group" aria-describedby="titleHelp">
                        <label for="title" class="input-group-text">{{ __('dicms::sites.site_title') }}</label>
                        <input type="text" name="title" id="title" value="{{ $site->title }}" class="form-control"
                               @cannot('update', $site) disabled @endcan />
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                    </div>
                    <div id="titleHelp" class="form-text mb-3">{{ __('dicms::sites.site_title_help') }}</div>

                    <div class="input-group" aria-describedby="menuHelp">
                        <label for="menu_id"
                               class="input-group-text">{{ __('dicms::sites.default_menu') }}</label>
                        <select name="menu_id" id="menu_id" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.select_default_menu') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Menu::all() as $menu)
                                <option value="{{ $menu->id }}"
                                        @if($menu->id == $site->menu_id) selected @endif>{{ $menu->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Menu::class)
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_menus') }}</a>
                        @endcan
                    </div>
                    <div id="menuHelp"
                         class="form-text mb-3">{{ __('dicms::sites.select_default_menu_help') }}</div>

                    <div class="input-group" aria-describedby="contentHeaderHelp">
                        <label for="header_id"
                               class="input-group-text">{{ __('dicms::sites.default_content_header') }}</label>
                        <select name="header_id" id="header_id" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.select_default_content_header') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Header::all() as $header)
                                <option value="{{ $header->id }}"
                                        @if($header->id == $site->header_id) selected @endif>{{ $header->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Header::class)
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.headers.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_content_headers') }}</a>
                        @endcan
                    </div>
                    <div id="contentHeaderHelp"
                         class="form-text mb-3">{{ __('dicms::sites.select_default_content_header_help') }}</div>

                    <div class="input-group" aria-describedby="footerHelp">
                        <label for="footer_id" class="input-group-text">{{ __('dicms::sites.default_footer') }}</label>
                        <select name="footer_id" id="footer_id" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.select_default_footer') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Footer::all() as $footer)
                                <option value="{{ $footer->id }}"
                                        @if($footer->id == $site->footer_id) selected @endif>{{ $footer->name }}</option>
                            @endforeach
                        </select>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Footer::class)
                            <button type="submit" class="btn btn-outline-primary"
                                    @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.footers.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_footers') }}</a>
                        @endcan
                    </div>
                    <div id="footerHelp"
                         class="form-text mb-3">{{ __('dicms::sites.select_default_footer_help') }}</div>

                    <div class="input-group" aria-describedby="homepageHelp">
                        <label for="homepage_url" class="input-group-text">{{ __('dicms::sites.home_page') }}</label>
                        <select name="homepage_url" id="homepage_url" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.select_homepage') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Page::all() as $page)
                                <option value="{{ $page->url }}"
                                        @if($page->url == $site->homepage_url) selected @endif>{{ $page->name }}</option>
                            @endforeach
                            @foreach(config('dicms.plugins', []) as $plugin)
                                @foreach($plugin::getPublicPages() as $page)
                                    <option value="{{ $page->url }}"
                                            @if($page->url == $site->homepage_url) selected @endif>{{ __('dicms::admin.plugin') }}: {{ $page->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Page::class)
                            <button type="submit" class="btn btn-outline-primary"
                                    @cannot('update', $site) disabled @endcan>{{ __('dicms::admin.update') }}</button>
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.pages.index', ['site' => $site->id]) }}"
                               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_pages') }}</a>
                        @endcan
                    </div>
                    <div id="homepageHelp" class="form-text mb-3">{{ __('dicms::sites.select_homepage_help') }}</div>
                </form>
            </div>

        </div>
        @can('activate', $site)
            @if($site->active)
                <div class="alert alert-danger mt-3">
                    <strong>{{ __('dicms::admin.danger') }}</strong> {{ __('dicms::errors.active.danger.deactivate') }}
                </div>
                <div class="row">
                    <a
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.disable', ['site' => $site->id]) }}"
                            role="button"
                            class="btn btn-danger col"
                    >{{ __('dicms::sites.disable') }}</a>
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.active.warning.activate') }}
                </div>
                <div class="row">
                    <a
                            role="button"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.enable', ['site' => $site->id]) }}"
                            class="btn btn-warning col"
                    >{{ __('dicms::sites.enable') }}</a>
                </div>
            @endif
        @endcan
        @can('archive', $site)
            @if(!$site->active)
                <div class="alert alert-warning mt-3">
                    <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.active.warning.archive') }}
                </div>
                <div class="row">
                    <a
                            role="button"
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.archive', ['site' => $site->id]) }}"
                            class="btn btn-warning col"
                    >{{ __('dicms::sites.archive') }}</a>
                </div>
            @endif
        @endcan
    </div>
@endsection
