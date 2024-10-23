@extends("dicms::layouts.admin", ['objEditable' => $objEditable?? null])

@section('content')
<div class="container">
    <h5 class="border-bottom d-flex justify-content-between align-items-end mb-3">
        <span class="pb-2">
            @if(isset($currentSite) && !isset($template['suppress_site_name'])) {{ $currentSite->name }} @endif
            @if($template['title'] && isset($currentSite) && !isset($template['suppress_site_name'])) : @endif
            @if($template['title']) {{ $template['title'] }} @endif
        </span>
        @isset($template['buttons'])
        <div class="h1">
            @foreach($template['buttons'] as $button)
                <a class="{{ $button['classes'] }} mx-2" href="{{ $button['link'] }}" role="button" title="{{ $button['title'] }}">{!! $button['text']  !!}</a>
            @endforeach
        </div>
        @endisset
    </h5>
    @yield('index_content')
    @isset($currentSite)
    <button
        class="position-fixed top-50 start-0 border-start-0 rounded-start-0 btn btn-outline-secondary p-3 "
        title="{{ __('dicms::assets.assets') }}"
        data-bs-toggle="offcanvas"
        data-bs-target="#asset-manager-side"
        aria-controls="asset-manager-side"
    >
        <i class="fa-solid fa-angles-right"></i>
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="asset-manager-side" aria-labelledby="asset-manager-label">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="asset-manager-label">{{ __('dicms::assets.assets') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('admin.close') }}"></button>
        </div>
        <div class="offcanvas-body">
            @isset($template['asset_action'])
                <livewire:asset-manager :mini="true" :selectAction="$template['asset_action']" />
            @else
                <livewire:asset-manager :mini="true" />
            @endisset
        </div>
    </div>
    @endisset
</div>
@endsection
