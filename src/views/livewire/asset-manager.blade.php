<div class="card @if($mini) asset-manager-mini @endif ">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span class="me-5">
            {{ __('dicms::assets.assets') }}
        </span>
        <div class="flex-grow-1 me-2">
            <input type="text" class="form-control" placeholder="{{ __('dicms::assets.search') }}" wire:model.live="filterTerms" value="{{ $filterTerms }}" />
        </div>
    </h5>
    <div class="card-body overflow-y-scroll" id="asset-viewer">
         <div class="row @if($mini) row-cols-2 @else row-cols-6 @endif">
            @forelse($assets as $asset)
                <div class="col mb-1">
                    <div
                        wire:key="{{ $asset->id }}"
                        url="{{ $asset->url }}"
                        class="card asset-image"
                        draggable="true"
                        @if($selectAction)
                            onclick='{{ $selectAction }}("{{ $asset->url }}")'
                        @endif
                    >
                        <img class="card-img" src="{{ $asset->thumb() }}" alt="{{ $asset->name }}" />
                        @if(!$selectAction)
                        <div class="card-img-overlay">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded" wire:click="viewItem({{ $asset->id }})"><i class="fa fa-search"></i></button>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded" wire:click="removeDataItem({{ $asset->id }})"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        @endif
                        <div class="card-footer">
                            <span
                                class="text-wrap fs-6"
                                @if(!$selectAction)
                                onclick="$(this).hide();$('#name-{{ $asset->id }}').removeClass('d-none');"
                                @endif
                            >{{ $asset->name }}</span>
                            <input
                                type="text"
                                id="name-{{ $asset->id }}"
                                value="{{ $asset->name }}"
                                class="form-control d-none"
                                wire:change="updateName({{ $asset->id }}, $event.target.value)"
                            />
                        </div>
                    </div>
                </div>
            @empty
                <div class="col col-12 w-100 h-100 d-flex justify-content-center align-items-center display-1">
                    {{ __('dicms::assets.search.no') }}
                </div>
            @endforelse
        </div>
        <div wire:loading.class.remove="d-none" class="position-absolute top-0 start-0 w-100 h-100 text-bg-secondary opacity-75 d-flex justify-content-center align-items-center d-none">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">{{ __('dicms::assets.drop') }}</span>
            </div>
        </div>

        <div
            class="position-absolute top-0 start-0 w-100 h-100 text-bg-secondary opacity-75 d-flex justify-content-center align-items-center d-none"
            id="ul-overlay"
            ondragleave="$(this).addClass('d-none')"
        >
            <span class="display-2">{{ __('dicms::assets.drop') }}</span>
        </div>
    </div>

    @if($viewingItem)
    <div class="position-fixed top-0 start-0 w-100 h-100 text-bg-secondary d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="card">
                <img class="card-img" src="{{ $viewingItem->url }}" alt="{{ $viewingItem->name }}" />
                <div class="card-img-overlay">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-danger" wire:click="closeView()"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@script
<script>
    window.addEventListener("dragover",function(e){
        e = e || event;
        e.preventDefault();
    },false);
    window.addEventListener("drop",function(e){
        e = e || event;
        e.preventDefault();
    },false);
    var dragging = false;

    $('.asset-image').on('dragstart', function (e)
    {
        dragging = true;
        e.originalEvent.dataTransfer.setData('text/uri-list', $(this).attr('url'));
    });

    $('.asset-image').on('dragend', function (e)
    {
        dragging = false;
    });

    $('#asset-viewer').on('dragenter', function(e)
    {

        if(!dragging)
        {
            $('#ul-overlay').removeClass('d-none');
        }
    });

    $('#ul-overlay').on('drop', function(e)
    {
        let files = e.originalEvent.dataTransfer.files;

        if(files.length === 1) {

            $wire.upload('dataItem', files[0], (uploadedFilename) => {
                $wire.addFile();
            }, () => {
                // Error callback...
            }, (event) => {
                // Progress callback...
                // event.detail.progress contains a number between 1 and 100 as the upload progresses
            }, () => {
                // Cancelled callback...
            });
        }
        else if(files.length > 1)
        {
            $wire.uploadMultiple('dataItem', files, (uploadedFilename) => {
                $wire.addFile();
            }, () => {
                // Error callback...
            }, (event) => {
                // Progress callback...
                // event.detail.progress contains a number between 1 and 100 as the upload progresses
            }, () => {
                // Cancelled callback...
            });
        }
    })

</script>
@endscript
