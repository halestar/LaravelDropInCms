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
            panels:
                {
                    defaults:
                        [
                            {
                                id: 'gjs-top-panel',
                                el: '#gjs-top-panel',
                            },
                            {
                                id: 'gjs-right-panel',
                                el: '#gjs-right-panel',
                            },
                            {
                                id: 'gjs-top-panel-switcher',
                                el: '#gjs-top-panel-switcher',
                                buttons:
                                    [
                                        {
                                            id: 'show-blocks',
                                            active: true,
                                            label: '<i class="fa-solid fa-object-group"></i>',
                                            command:
                                                {
                                                    run(editor, sender) {
                                                        $('#gjs-right-blocks-container').show();
                                                    },
                                                    stop(editor, sender) {
                                                        $('#gjs-right-blocks-container').hide();
                                                    },
                                                },
                                            togglable: false,
                                        },
                                        {
                                            id: 'show-style',
                                            active: true,
                                            label: '<i class="fa-solid fa-paintbrush"></i>',
                                            command:
                                                {
                                                    run(editor, sender) {
                                                        $('#gjs-right-styles-container').show();
                                                    },
                                                    stop(editor, sender) {
                                                        $('#gjs-right-styles-container').hide();
                                                    },
                                                },
                                            togglable: false,
                                        },
                                        {
                                            id: 'show-traits',
                                            active: true,
                                            label: '<i class="fa-solid fa-gear"></i>',
                                            command:
                                                {
                                                    run(editor, sender) {
                                                        $('#gjs-right-traits-container').show();
                                                    },
                                                    stop(editor, sender) {
                                                        $('#gjs-right-traits-container').hide();
                                                    },
                                                },
                                            togglable: false,
                                        },
                                        {
                                            id: 'show-layers',
                                            active: true,
                                            label: '<i class="fa-solid fa-layer-group"></i>',
                                            command:
                                                {
                                                    run(editor, sender) {
                                                        $('#gjs-right-layers-container').show();
                                                    },
                                                    stop(editor, sender) {
                                                        $('#gjs-right-layers-container').hide();
                                                    },
                                                },
                                            // Once activated disable the possibility to turn it off
                                            togglable: false,
                                        }
                                    ],
                            },
                            {
                                id: 'gjs-top-device-view',
                                el: '#gjs-top-device-view',
                                buttons: [
                                    {
                                        id: 'device-desktop',
                                        active: true,
                                        label: '<i class="fa-solid fa-computer"></i>',
                                        command(editor) {
                                            editor.Devices.select('desktop');
                                        },
                                    },
                                    {
                                        id: 'device-tablet',
                                        active: false,
                                        label: '<i class="fa-solid fa-tablet-screen-button"></i>',
                                        command(editor) {
                                            editor.Devices.select('tablet');
                                        },
                                    },
                                    {
                                        id: 'device-mobileLandscape',
                                        active: false,
                                        label: '<i class="fa-solid fa-mobile-screen fa-rotate-90"></i>',
                                        command(editor) {
                                            editor.Devices.select('mobileLandscape');
                                        },
                                    },
                                    {
                                        id: 'device-mobilePortrait',
                                        active: false,
                                        label: '<i class="fa-solid fa-mobile-screen"></i>',
                                        command(editor) {
                                            editor.Devices.select('mobilePortrait');
                                        },
                                    },
                                ],
                            },
                            {
                                id: 'gjs-top-page-view',
                                el: '#gjs-top-page-view',
                                buttons: [
                                    {
                                        id: 'sw-visibility',
                                        label: '<i class="fa-regular fa-square"></i>',
                                        active: true,
                                        command: "core:component-outline",
                                        context: "sw-visibility",
                                    },
                                    {
                                        id: 'preview',
                                        active: false,
                                        label: '<i class="fa fa-eye"></i>',
                                        command: "core:preview",
                                        context: "preview",
                                    },
                                    {
                                        id: 'fullscreen',
                                        active: false,
                                        label: '<i class="fa-solid fa-maximize"></i>',
                                        command:
                                            {
                                                run(editor) {
                                                    editor.runCommand('core:fullscreen', {target: '#gjs-container'});
                                                },
                                                stop(editor) {
                                                    editor.stopCommand('core:fullscreen');
                                                }
                                            },
                                        context: "fullscreen",
                                    },
                                    {
                                        id: 'code',
                                        active: false,
                                        label: '<i class="fa-solid fa-code"></i>',
                                        command: "core:open-code",
                                    },
                                ],
                            }
                        ]
                },
            styleManager:
                {
                    clearProperties: true,
                    appendTo: '#gjs-right-styles-container',
                },
            selectorManager: {
                appendTo: '#gjs-right-styles-container',
            },
            layerManager: {
                appendTo: '#gjs-right-layers-container',
            },
            traitManager: {
                appendTo: '#gjs-right-traits-container',
            },
            blockManager: {
                appendTo: '#gjs-right-blocks-container'
            },
            pluginsOpts:
                {},
            canvas:
                {
                    styles: {!! $objEditable->CssLinks() !!},
                    scripts: {!! $objEditable->JsLinks() !!},
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
