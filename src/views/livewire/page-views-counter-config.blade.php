<div class="container">
    <div class="row">
        <div class="col-4">
            <h4>{{ __('dicms::widgets.pages') }}</h4>
            <ul class="list-group">
                @foreach($pages as $page)
                    <li class="list-group-item list-group-item-action" wire:key="{{ $page->id }}" wire:click="viewPage({{ $page->id }})">
                        {{ $page->name }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-8">
            @isset($selectedPage)
                <h5>{{ __('dicms::widgets.total') }} {{ \halestar\LaravelDropInCms\Models\PageView::totalViews($page) }}</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('dicms::widgets.ip') }}</th>
                        <th>{{ __('dicms::widgets.viewed') }}</th>
                        <th>{{ __('dicms::widgets.first') }}</th>
                        <th>{{ __('dicms::widgets.last') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($viewers as $viewer)
                        <tr>
                            <td>{{ $viewer->ip_address }}</td>
                            <td>{{ $viewer->views }}</td>
                            <td>{{ $viewer->created_at->format(config('dicms.datetime_format')) }}</td>
                            <td>{{ $viewer->updated_at->format(config('dicms.datetime_format')) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $viewers->links('dicms::pagination.bootstrap') }}
            @endisset
        </div>
    </div>
</div>
