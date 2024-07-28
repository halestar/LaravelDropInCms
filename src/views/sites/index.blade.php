@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            {{__('dicms::sites.sites_title')}}
            @can('create', \halestar\LaravelDropInCms\Models\Site::class)
            <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.create') }}" role="button">{{ __('dicms::sites.new_site') }}</a>
            @endcan
        </h1>
        <div class="list-group">
            @foreach(\halestar\LaravelDropInCms\Models\Site::all() as $site)
                @can('view', $site)
                <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" class="list-group-item list-group-item-action">
                    {{ $site->name }}
                </a>
                @endcan
            @endforeach
        </div>
    </div>
@endsection
