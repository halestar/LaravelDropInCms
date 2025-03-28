<div class="card @if($mini) asset-manager-mini @endif ">
    <h5 class="card-header d-flex justify-content-start align-items-center">
        <span class="pe-2 border-end">
            {{ __('dicms::assets.assets') }}
        </span>
        <button class="btn btn-outline-light" wire:click="addFolder()">
            <i class="fa-solid fa-folder-plus text-warning"></i>
        </button>
        <div class="flex-grow-1 me-2">
            <div class="input-group">
                <input
                    type="text"
                    id="assets-filter-term"
                    class="form-control border-end-0 border rounded-start-pill"
                    placeholder="{{ __('dicms::assets.search') }}"
                    wire:model.live="filterTerms"
                    value="{{ $filterTerms }}"
                />
                <button
                    class="btr btn-outline-secondary bg-white border-start-0 border rounded-end-pill pe-3"
                    wire:click="clearFiter()"
                >
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </h5>
    <div class="card-body overflow-y-scroll" id="asset-viewer">
         <div class="row @if($mini) row-cols-2 @else row-cols-6 @endif">
            @if($selectedFolder)
                 <div class="col mb-3">
                     <div
                         wire:key="{{ $selectedFolder->parent_id }}"
                         class="card asset-folder border-0 overflow-hidden"
                         draggable="true"
                         @if($selectedFolder->parent_id)
                         wire:click='viewFolder({{ $selectedFolder->parent_id }})'
                         @else
                             wire:click="viewRoot()"
                         @endif
                         style="height: 100px;"
                         di-id="{{ $selectedFolder->parent_id }}"
                     >
                    <span class="m-auto fa-stack fa-2x">
                        <i class="fa-solid fa-folder fa-stack-2x text-warning"></i>
                        <i class="fa-solid fa-turn-up fa-stack-1x"></i>
                    </span>
                     </div>
                     <div class="border-bottom border-start border-end rounded-bottom py-1 px-0 text-bg-light text-center">
                         <h1>..</h1>
                     </div>
                 </div>
            @endif
            @foreach($assets as $asset)
                <div class="col mb-3">
                    @if($asset->is_folder)
                        <div
                            wire:key="{{ $asset->id }}"
                            class="card asset-folder border-0 overflow-hidden"
                            draggable="true"
                            droppable="true"
                            x-on:dblclick='$wire.viewFolder("{{ $asset->id }}")'
                            style="height: 100px;"
                            di-id="{{ $asset->id }}"
                        >
                            <img class="card-img my-auto d-block h-100" src="{{ $asset->thumb() }}" alt="{{ $asset->name }}" />
                            <div class="card-img-overlay">
                                <div class="d-flex justify-content-end">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm rounded"
                                        wire:click="removeDataItem({{ $asset->id }})"
                                        wire:confirm="{{ __('dicms::assets.delete.confirm') }}"
                                    ><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom border-start border-end rounded-bottom py-1 px-0 text-bg-light">
                            <div
                                class="row align-items-center w-100 p-0 m-0"
                                id="span-{{ $asset->id }}"
                            >
                                <div
                                    class="text-wrap fs-6 col-12"
                                    @if(!$selectAction)
                                        onclick="$('#span-{{ $asset->id }}').hide();$('#name-{{ $asset->id }}').removeClass('d-none');"
                                    @endif
                                >
                                    {{ $asset->name }}
                                </div>
                            </div>
                            <div
                                class="input-group d-none"
                                id="name-{{ $asset->id }}"
                            >
                                <input
                                    type="text"
                                    value="{{ $asset->name }}"
                                    id="input-{{ $asset->id }}"
                                    class="form-control form-control-sm"
                                />
                                <button
                                    type="button"
                                    class="btn btn-sm btn-success"
                                    wire:click="updateName({{ $asset->id }}, $('#input-{{ $asset->id }}').val())"
                                ><i class="fa fa-check"></i></button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="$('#span-{{ $asset->id }}').show();$('#name-{{ $asset->id }}').addClass('d-none');"
                                ><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    @else
                        <div
                            wire:key="{{ $asset->id }}"
                            url="{{ $asset->url }}"
                            class="card asset-image rounded-bottom-0 overflow-hidden"
                            draggable="true"
                            @if($selectAction)
                                onclick='{{ $selectAction }}("{{ $asset->url }}")'
                            @endif
                            style="height: 100px;"
                            di-id="{{ $asset->id }}"
                        >
                            <img class="card-img my-auto d-block" src="{{ $asset->thumb() }}" alt="{{ $asset->name }}" />
                            @if(!$selectAction)
                            <div class="card-img-overlay">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded" wire:click="viewItem({{ $asset->id }})"><i class="fa fa-search"></i></button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm rounded"
                                        wire:click="removeDataItem({{ $asset->id }})"
                                        wire:confirm="{{ __('dicms::assets.delete.confirm') }}"
                                    ><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="border-bottom border-start border-end rounded-bottom py-1 px-0 text-bg-light">
                            <div
                                class="row align-items-center w-100 p-0 m-0"
                                id="span-{{ $asset->id }}"
                            >
                                <div
                                    class="text-wrap fs-6 col-8"
                                    @if(!$selectAction)
                                        onclick="$('#span-{{ $asset->id }}').hide();$('#name-{{ $asset->id }}').removeClass('d-none');"
                                    @endif
                                >
                                    {{ $asset->name }}
                                </div>
                                <div class="col-4 pe-0 me-0">
                                    <button
                                        class="btn btn-primary btn-sm ms-auto d-block"
                                        onclick="navigator.clipboard.writeText('{{ $asset->url }}')"
                                        data-bs-toggle="popover"
                                        data-bs-content="{{ __('dicms::admin.copied') }}"
                                        data-bs-trigger="focus"
                                    >
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                </div>
                            </div>

                            <div
                                class="input-group d-none"
                                id="name-{{ $asset->id }}"
                            >
                                <input
                                    type="text"
                                    value="{{ $asset->name }}"
                                    id="input-{{ $asset->id }}"
                                    class="form-control form-control-sm"
                                />
                                <button
                                    type="button"
                                    class="btn btn-sm btn-success"
                                    wire:click="updateName({{ $asset->id }}, $('#input-{{ $asset->id }}').val())"
                                ><i class="fa fa-check"></i></button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="$('#span-{{ $asset->id }}').show();$('#name-{{ $asset->id }}').addClass('d-none');"
                                ><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
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
    <div class="position-fixed top-0 start-0 w-100 h-100 text-bg-dark d-flex justify-content-center align-items-center z-2 opacity-75">
        <button type="button" class="btn btn-danger position-fixed top-0 end-0" wire:click="closeView()"><i class="fa fa-times"></i></button>
        <div class="card z-3">
            <img class="card-img" src="{{ $viewingItem->url }}" alt="{{ $viewingItem->name }}" />
        </div>
    </div>
    @endif
</div>
@script
<script>

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    // prevent all the defaults first
    window.addEventListener("dragover",function(e){
        e = e || event;
        e.preventDefault();
    },false);
    window.addEventListener("drop",function(e){
        e = e || event;
        e.preventDefault();
    },false);

    //set the global dragging var.
    window.asset_dragging = false;

    function redoDroppable()
    {
        window.asset_dragging = false;
        $('.asset-image').off('dragstart,dragend');
        $('.asset-folder').off('dragenter,dragleave,drop');
        $('#asset-viewer').off('dragenter');
        $('#ul-overlay').off('drop');
        // Moving Asset Images
        /**
         * This function sets the global dragging to true so that that
         * the overlay won't display, and sets the URL of the item as and
         * the id of the item as data transfer data.
         */
        $('.asset-image').on('dragstart', function (e)
        {
            window.asset_dragging = true;
            e.originalEvent.dataTransfer.setData('text/uri-list', $(this).attr('url'));
            e.originalEvent.dataTransfer.setData('text/di-id', $(this).attr('di-id'));
        });

        /**
         * When dragging ends (no dropping) reset the variable.
         */
        $('.asset-image').on('dragend', function (e)
        {
            window.asset_dragging = false;
        });

        //set up the folder actions.
        /**
         * This function sets the global dragging to true so that that
         * the overlay won't display, and sets the URL of the item as and
         * the id of the item as data transfer data.
         */
        $('.asset-folder').on('dragstart', function (e)
        {
            window.asset_dragging = true;
            e.originalEvent.dataTransfer.setData('text/di-id', $(this).attr('di-id'));
        });

        /**
         * When dragging ends (no dropping) reset the variable.
         */
        $('.asset-folder').on('dragend', function (e)
        {
            window.asset_dragging = false;
        });
        /**
         * When an internal item being dragged (dragged is true) enters a folder,
         * highlight the folder.
         */
        $('.asset-folder').on('dragenter', function (e)
        {
            //we only do this when dragging an element
            if(window.asset_dragging)
            {
                $(this).removeClass('border-0').addClass('border-2').addClass('border-primary');
            }
        });

        /**
         * When leaving, we remove the classes.
         */
        $('.asset-folder').on('dragleave', function (e)
        {
            if(window.asset_dragging)
            {
                $(this).removeClass('border-2').removeClass('border-primary').addClass('border-0');
            }
        });

        /**
         * When a dragging asset is being dropped, we asume it's a move to folder operation
         */
        $('.asset-folder').on('drop', function (e)
        {
            if(window.asset_dragging)
            {
                let item_id = e.originalEvent.dataTransfer.getData('text/di-id');
                let folder_id = $(this).attr('di-id');
                if(folder_id === "")
                    $wire.moveToRoot(item_id);
                else
                    $wire.moveDataItem(item_id, folder_id);
                //reset the dragging
                console.log('window.asset_dragging set to false');
                window.asset_dragging = false;
                $(this).removeClass('border-2').removeClass('border-primary').addClass('border-0');
            }
        });


        // Uploading Files
        /**
         * This function will enable an overlay when dragging something into
         * the container
         */
        $('#asset-viewer').on('dragenter', function(e)
        {
            if(!window.asset_dragging)
            {
                $('#ul-overlay').removeClass('d-none');
            }
        });

        /**
         * This function will deal with any files that are dropped into the
         * overlay.
         */
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
        });
    }

    redoDroppable();

    Livewire.hook('morphed',  ({ el, component }) => {
        redoDroppable();
    })


</script>
@endscript
