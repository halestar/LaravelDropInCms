<script>
    const editor = grapesjs.init(
        {
            // Indicate where to init the editor. You can also pass an HTMLElement
            container: '{{ $eConfig['editor'] }}',
            // Get the content for the canvas directly from the element
            // As an alternative we could use: `components: '<h1>Hello World Component!</h1>'`,
            fromElement: true,
            // Size of the editor
            height: '400px',
            width: 'auto',
            storageManager: false,
            // Disable the storage manager for the moment
            plugins:
                [
                    "gjs-blocks-basic",
                    'grapesjs-style-bg',
                    'grapesjs-plugin-ckeditor',
                    'grapesjs-style-gradient',
                    plhPlugin
                ],
            pluginsOpts:
                {},
            canvas:
                {
                    styles: [ {!! $eConfig['styles'] !!}],
                    scripts: [ {!! $eConfig['scripts'] !!} ],
                },
            assetManager:
                {
                    custom:
                        {
                            open(props)
                            {
                                if(!this.myModal)
                                    this.myModal = new bootstrap.Modal('#grapesjs-assets-modal');
                                $('#grapesjs-assets-modal').on('hidden.bs.modal', function()
                                    {
                                        props.close();
                                    })
                                    .on('asset-selected', function(e, url)
                                    {
                                        props.select({type: "image", src: url}, true);
                                    });
                                this.myModal.show();
                            },
                            close(props)
                            {
                                if(this.myModal)
                                    this.myModal.hide();
                            },
                        }
                }
        });

    @if($eConfig['projectData'])
    editor.loadProjectData({!! $eConfig['projectData'] !!});
    @endif

    function addAssetToGrapesJs(url)
    {
        $('#grapesjs-assets-modal').trigger('asset-selected', [url]);
    }
</script>
<div class="modal" tabindex="-1" id="grapesjs-assets-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <livewire:asset-manager selectAction="addAssetToGrapesJs" />
        </div>
    </div>
</div>
