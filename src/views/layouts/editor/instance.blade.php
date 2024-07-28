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
                    assets: ['{{ implode("','", \halestar\LaravelDropInCms\DiCMS::assets()) }}'],
                    upload: '{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.upload') }}',
                    uploadName: 'files',
                    autoAdd: true,
                    custom: false,
                    headers:
                        {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                }
        });

    @if($eConfig['projectData'])
    editor.loadProjectData({!! $eConfig['projectData'] !!});
    @endif
    @foreach(\halestar\LaravelDropInCms\DiCMS::assets() as $asset)
    editor.AssetManager.add('{!! $asset !!}');
    @endforeach


</script>
