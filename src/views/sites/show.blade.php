@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
        @if($site->active)
            <div class="alert alert-warning">
                <strong>{{ __('dicms::admin.warning') }}</strong> {{ __('dicms::errors.active.warning') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md">
                <livewire:css-sheet-manager :siteId="$site->id" :container="$site" />
                <br />
                <livewire:js-script-manager :siteId="$site->id" :container="$site" />
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
                                @cannot('update', $site) disabled @endcan title="{{ __('dicms::admin.update') }}"><i class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                    <div id="titleHelp" class="form-text mb-3">{{ __('dicms::sites.site_title_help') }}</div>

                    <div class="input-group" aria-describedby="headerHelp">
                        <label for="header_id"
                               class="input-group-text">{{ __('dicms::sites.default_header') }}</label>
                        <select name="header_id" id="header_id" class="form-select"
                                @cannot('update', $site) disabled @endcan>
                            <option value="">{{ __('dicms::sites.select_default_header') }}</option>
                            @foreach(\halestar\LaravelDropInCms\Models\Header::all() as $header)
                                <option value="{{ $header->id }}"
                                        @if($header->id == $site->header_id) selected @endif>{{ $header->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan title="{{ __('dicms::admin.update') }}"><i class="fa-solid fa-floppy-disk"></i></button>
                        @if($site->header_id)
                            @can('update', $site->defaultHeader)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.edit', ['header' => $site->header_id]) }}"
                                    class="btn btn-outline-info" title="{{ __('dicms::headers.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endcan
                        @endif
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Header::class)
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.headers.index') }}"
                               class="btn btn-outline-secondary" title="{{ __('dicms::admin.manage_headers') }}"><i class="fa-solid fa-bars-progress"></i></a>
                        @endcan
                    </div>
                    <div id="headerHelp"
                         class="form-text mb-3">{{ __('dicms::sites.select_default_header_help') }}</div>

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

                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan title="{{ __('dicms::admin.update') }}"><i class="fa-solid fa-floppy-disk"></i></button>
                        @if($site->footer_id)
                            @can('update', $site->defaultFooter)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.edit', ['footer' => $site->footer_id]) }}"
                                    class="btn btn-outline-info" title="{{ __('dicms::headers.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endcan
                        @endif
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Footer::class)
                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.index') }}"
                               class="btn btn-outline-secondary" title="{{ __('dicms::admin.manage_footers') }}"><i class="fa-solid fa-bars-progress"></i></a>
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
                        <button type="submit" class="btn btn-outline-primary"
                                @cannot('update', $site) disabled @endcan title="{{ __('dicms::admin.update') }}"><i class="fa-solid fa-floppy-disk"></i></button>
                        @can('viewAny', \halestar\LaravelDropInCms\Models\Page::class)

                            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.index') }}"
                               class="btn btn-outline-secondary" title="{{ __('dicms::admin.manage_pages') }}"><i class="fa-solid fa-bars-progress"></i></a>
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
@endsection
