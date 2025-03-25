@extends("dicms::layouts.admin.index", ['template' => $template, 'excludeAssetManager' => true, 'currentSite' => $site])

@section('index_content')
    <h3>{{ __('dicms::pages.import.select') }}</h3>
    <form method="POST" action="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.pages.import', ['site' => $site->id]) }}">
        @csrf
        <div class="d-flex flex-wrap align-items-start">
            @foreach(\halestar\LaravelDropInCms\Models\Site::whereNot('id', $site->id)->get() as $site)
                <div>
                    <h4 class="border-bottom my-3 text-center">{{ $site->name }}</h4>
                    <ul class="list-group">
                        @foreach($site->pages as $page)
                            <li class="list-group-item list-group-item-action">
                                <input
                                    type="checkbox"
                                    name="pages[]"
                                    value="{{ $page->id }}"
                                    id="header_{{ $page->id }}"
                                    class="form-check-input me-1"
                                />
                                <label class="form-check-label stretched-link" for="header_{{ $page->id }}">{{ $page->name }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <div class="row mt-3">
            <button type="submit" class="btn btn-primary">{{ __('dicms::pages.import') }}</button>
        </div>
    </form>
@endsection
