@extends("dicms::layouts.admin.index", ['template' => $template])

@section('index_content')
    @if(\halestar\LaravelDropInCms\Models\Footer::count() > 0)
        <div class="ms-1 row">
            <div class="col-2">{{ __('dicms::admin.name') }}</div>
            <div class="col-9">{{ __('dicms::admin.description') }}</div>
        </div>
        <ul class="list-group">
            @foreach(\halestar\LaravelDropInCms\Models\Footer::all() as $footer)
                <li class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-2">{{ $footer->name }}</div>
                        <div class="col-8 text-muted small">{{ $footer->description }}</div>
                        <div class="col-2 text-end">
                            @can('create', \halestar\LaravelDropInCms\Models\Footer::class)
                            <a
                                role="button"
                                href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.edit', ['footer' => $footer->id]) }}"
                                class="btn btn-primary btn-sm ms-2"
                                title="{{ __('dicms::admin.edit') }}"
                            ><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('create', \halestar\LaravelDropInCms\Models\Footer::class)
                                <a
                                    href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.duplicate', ['footer' => $footer->id]) }}"
                                    role="button"
                                    class="btn btn-warning btn-sm ms-2"
                                    title="{{ __('dicms::footers.duplicate') }}"
                                ><i class="fa-solid fa-clone"></i></a>
                            @endcan
                            @can('delete', \halestar\LaravelDropInCms\Models\Footer::class)
                            <button
                                type="button"
                                onclick="confirmDelete('{{ __('dicms::footers.delete.confirm') }}', '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.footers.destroy', ['footer' => $footer->id]) }}')"
                                class="btn btn-danger btn-sm ms-2"
                                title="{{ __('dicms::admin.delete') }}"
                            ><i class="fa fa-trash"></i></button>
                            @endcan
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-info">
            {{ __('dicms::footers.footer.none') }}
        </div>
    @endif
@endsection
