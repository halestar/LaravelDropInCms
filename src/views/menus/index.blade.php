@extends("dicms::layouts.admin")

@section('content')
    <div class="container">
        <h1 class="border-bottom d-flex justify-content-between align-items-center">
            <span>{{ $site->name }}: {{__('dicms::menus.menus.title')}}</span>
            <div>
                <a class="btn btn-primary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.create', ['site' => $site->id]) }}" role="button">{{ __('dicms::menus.new') }}</a>
                <a class="btn btn-secondary" href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]) }}" role="button">{{ __('dicms::menus.back') }}</a>
            </div>
        </h1>
        <ul class="list-group">
            @foreach($site->menus as $menu)
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a
                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.edit', ['site' => $site->id, 'menu' => $menu->id]) }}"
                    class=""
                >
                    {{ $menu->name }}
                </a>
                <button
                    type="button"
                    onclick="confirmDelete('{{ __('dicms::menus.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.menus.destroy', ['site' => $site->id, 'menu' => $menu->id]) }}')"
                    class="btn btn-danger"
                >{{ __('dicms::admin.delete') }}</button>
            @endforeach
        </ul>
    </div>
@endsection
