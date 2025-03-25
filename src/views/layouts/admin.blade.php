@use('halestar\LaravelDropInCms\Models\Site')
@use('\halestar\LaravelDropInCms\DiCMS')
@if(!isset($currentSite) || (!$currentSite && Site::count() > 0))
    @php
        $currentSite = Site::activeSite();
        if(!$currentSite)
            $currentSite = Site::first();
    @endphp
@endif
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Drop-In Content Management System') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d18ee59f88.js" crossorigin="anonymous"></script>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @livewireStyles
    <!-- Scripts -->
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    @stack('head_scripts')


    <script>
        function confirmDelete(msg, url)
        {
            event.stopPropagation();
            if(confirm(msg))
            {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                var delInput = document.createElement('input');
                delInput.type = 'hidden';
                delInput.name = '_method';
                delInput.value = 'DELETE';
                form.appendChild(delInput);
                var csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = jQuery('meta[name="csrf-token"]').attr('content');
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md" style="background-image: linear-gradient(#50b3eb,#2fa4e7 60%,#2c9ad9)" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand" href="{{ config('dicms.back_to_url') }}">
                {{ config('app.name', 'DiCMS') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    @if(Site::count() > 0)
                        @can("viewAny", \halestar\LaravelDropInCms\Models\DataItem::class)
                            <li class="nav-item">
                                <a
                                    href="{{ DiCMS::dicmsRoute('admin.assets.index') }}"
                                    @if(DiCMS::inAdminModule('assets'))
                                        class="nav-link active"
                                    aria-current="page"
                                    @else
                                        class="nav-link"
                                    @endif
                                >
                                    {{ __('dicms::admin.assets_menu_item') }}
                                </a>
                            </li>
                        @endcan
                        @foreach(config('dicms.plugins') as $plugin)
                            @can('viewAny', $plugin::getPolicyModel())
                                    <li class="nav-item">
                                        <a
                                            href="{{ $plugin::getAdminUrl() }}"
                                            @if(DiCMS::inAdminModule($plugin::getRoutePrefix()))
                                                class="nav-link active"
                                            aria-current="page"
                                            @else
                                                class="nav-link"
                                            @endif
                                        >
                                            {{ $plugin::getPluginMenuName() }}
                                        </a>
                                    </li>
                            @endcan
                        @endforeach
                        @can('widgets', Site::class)
                            <li class="nav-item">
                                <a
                                    href="{{ DiCMS::dicmsRoute('admin.widgets.index') }}"
                                    @if(DiCMS::inAdminModule('widgets'))
                                        class="nav-link active"
                                    aria-current="page"
                                    @else
                                        class="nav-link"
                                    @endif
                                >
                                    {{ trans_choice('dicms::admin.widget', 2) }}
                                </a>
                            </li>
                        @endcan
                    @else
                        <li class="nav-item">
                            <a
                                href="{{ DiCMS::dicmsRoute('admin.sites.create') }}"
                                class="nav-link"
                            >
                                {{ __('dicms::sites.new_site') }}
                            </a>
                        </li>
                    @endif
                    @can('backup', Site::class)
                        <li class="nav-item">
                            <a
                                href="{{ DiCMS::dicmsRoute('admin.backups.index') }}"
                                @if(DiCMS::inAdminModule('backup'))
                                    class="nav-link active"
                                aria-current="page"
                                @else
                                    class="nav-link"
                                @endif
                            >
                                {{ __('dicms::admin.backups') }}
                            </a>
                        </li>
                    @endcan
                </ul>

                @if(Site::count() > 0)
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <div class="input-group">
                            <label class="input-group-text" for="current-site">{{ __('dicms::sites.site') }}</label>
                            <select
                                class="form-select"
                                id="current-site"
                                name="current_site"
                                onchange="window.location.href='{{ DiCMS::dicmsRoute('admin.home') }}/sites/' + $(this).val()"
                            >
                                @foreach(Site::all() as $site)
                                    <option value="{{ $site->id }}" @selected($currentSite->id == $site->id) >{{ $site->name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ DiCMS::dicmsRoute('admin.sites.show', ['site' => $currentSite->id]) }}" class="btn btn-outline-dark" type="button" title="{{ __('dicms::sites.site.current') }}"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ DiCMS::dicmsRoute('admin.sites.create') }}" class="btn btn-outline-success" type="button" title="{{ __('dicms::sites.new_site') }}"><i class="fa-regular fa-square-plus"></i></a>
                            <a href="{{ DiCMS::dicmsRoute('admin.sites.index') }}" class="btn btn-outline-primary" type="button" title="{{ __('dicms::sites.sites_title') }}"><i class="fa-solid fa-bars-progress"></i></a>
                        </div>
                    </li>
                </ul>
                @endif

            </div>
        </div>
    </nav>
    @if($errors->count() > 0 && env('APP_DEBUG'))
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <strong>Whoops! Something went wrong!</strong>
                        <br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <main class="py-4">
        @yield('content')
    </main>
    @isset($currentSite)
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @session('success-status')
        <div class="toast show" data-bs-delay="5000" role="alert" aria-live="assertive" aria-atomic="true" id="success-toast">
            <div class="toast-header bg-primary-subtle">
                <strong class="me-auto">{{ __('dicms::admin.success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $value }}
            </div>
        </div>
        <script>
            $(document).ready(function()
                {
                    setTimeout(function()
                    {
                        $('#success-toast').hide();
                    }, 5000)
                })
        </script>
        @endsession
    </div>
    @endisset
</div>

@stack('scripts')
</body>
</html>
