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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @livewireStyles
    <!-- Scripts -->
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @isset($include_editor)
        @include('dicms::layouts.editor.config', ['eConfig' => $include_editor])
    @endisset

    @isset($include_text_editor)
        @include('dicms::layouts.text-editor.config',['eConfig' => $include_text_editor])
    @endisset

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
    <nav class="navbar navbar-expand-md bg-primary" data-bs-theme="dark">
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
                    @can("viewAny", \halestar\LaravelDropInCms\Models\Site::class)
                        <li class="nav-item">
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.index') }}"
                                @if(\halestar\LaravelDropInCms\DiCMS::inAdminModule('sites'))
                                    class="nav-link active"
                                    aria-current="page"
                                @else
                                    class="nav-link"
                                @endif
                            >
                                {{ __('dicms::admin.site_menu_item') }}
                            </a>
                        </li>
                    @endcan
                    @can("viewAny", \halestar\LaravelDropInCms\Models\DataItem::class)
                        <li class="nav-item">
                            <a
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.assets.index') }}"
                                @if(\halestar\LaravelDropInCms\DiCMS::inAdminModule('assets'))
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
                        @can('viewAny', $plugin::getEntryPoint()->getPolicyModel())
                                <li class="nav-item">
                                    <a
                                        href="{{ $plugin::getEntryPoint()->getAdminUrl() }}"
                                        @if(\halestar\LaravelDropInCms\DiCMS::inAdminModule($plugin::getEntryPoint()->getRoutePrefix()))
                                            class="nav-link active"
                                        aria-current="page"
                                        @else
                                            class="nav-link"
                                        @endif
                                    >
                                        {{ $plugin::getEntryPoint()->getPluginMenuName() }}
                                    </a>
                                </li>
                        @endcan
                    @endforeach
                    @can('backup', \halestar\LaravelDropInCms\Models\Site::class)
                            <li class="nav-item">
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.backups.index') }}"
                                    @if(\halestar\LaravelDropInCms\DiCMS::inAdminModule('backup'))
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
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @session('success-status')
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-primary-subtle">
                <strong class="me-auto">{{ __('common.success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $value }}
            </div>
        </div>
        @endsession
    </div>
</div>

@isset($include_editor)
    @include('dicms::layouts.editor.instance', ['eConfig' => $include_editor])
@endisset

@isset($include_text_editor)
    @include('dicms::layouts.text-editor.instance',['eConfig' => $include_text_editor])
@endisset


@stack('scripts')
</body>
</html>
