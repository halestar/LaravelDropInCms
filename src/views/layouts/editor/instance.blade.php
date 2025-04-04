<script>
    const editor = grapesjs.init(
        {
            // Indicate where to init the editor. You can also pass an HTMLElement
            container: '#grapes-js-editor',
            // Get the content for the canvas directly from the element
            // As an alternative we could use: `components: '<h1>Hello World Component!</h1>'`,
            fromElement: true,
            // Size of the editor
            height: '100%',
            width: 'auto',
            storageManager: false,
            // Disable the storage manager for the moment
            plugins:
                [
                    'grapesjs-style-bg',
                    'grapesjs-style-gradient',
                    plhPlugin,
                    @foreach(config('dicms.plugins') as $plugin)
                        @foreach($plugin::getGrapesJsPlugins() as $editorPlugin)
                            @if($editorPlugin->shouldInclude($objEditable))
                                {{ $editorPlugin->getPluginName() }},
                             @endif
                        @endforeach
                    @endforeach
                ],
            styleManager:
                {
                    clearProperties: true,
                },
            pluginsOpts:
                {},
            canvas:
                {
                    styles: {!! $objEditable->CssLinks() !!},
                    scripts: {!! $objEditable->JsLinks() !!},
                },
            blockManager:
                {
                    appendTo: '#blocks-container'
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

    @if($objEditable->projectData())
    editor.loadProjectData({!! $objEditable->projectData() !!});
    @endif

    function addAssetToGrapesJs(url)
    {
        $('#grapesjs-assets-modal').trigger('asset-selected', [url]);
    }
    editor.on('component:selected', (selectedComponent) =>
    {
        const commandToAdd = 'clear-style';
        const commandIcon = 'fa-solid fa-broom';

        // get the selected componnet and its default toolbar
        const defaultToolbar = selectedComponent.get('toolbar');

        const commandExists = defaultToolbar.some(item => item.command === commandToAdd);
        if (!commandExists) {
            selectedComponent.set({
                toolbar: [ ...defaultToolbar, {  attributes: {class: commandIcon}, command: commandToAdd }]
            });
        }
    });
</script>
<div class="modal" tabindex="-1" id="grapesjs-assets-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <livewire:asset-manager selectAction="addAssetToGrapesJs" />
        </div>
    </div>
</div>
