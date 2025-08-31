<div {{ $attributes }}>
    <div class="mb-3 d-flex justify-content-between align-items-bottom border-bottom">
        <div>
            <label for="footer" class="form-label">{{ $title }}</label>
            <div id="footerHelp" class="form-text ">{{ $help }}</div>
        </div>
    </div>
    <div id="gjs-container">
        <div class="row gx-0 mw-100" style="height: 40px;" id="gjs-top-panel">
            <div class="col-4" id="gjs-top-panel-switcher">
            </div>
            <div class="col-4 d-flex justify-content-center" id="gjs-top-page-view">
            </div>
            <div class="col-4 d-flex justify-content-end pe-3" id="gjs-top-device-view">
            </div>
        </div>
        <div class="row gx-0 mw-100" style="height: 800px; max-height: 800px;" id="">
            <div class="col-2 m-0 overflow-auto" id="gjs-right-panel" style="height: 800px; max-height: 800px;">
                <div id="gjs-right-layers-container"></div>
                <div id="gjs-right-styles-container"></div>
                <div id="gjs-right-traits-container"></div>
                <div id="gjs-right-blocks-container"></div>
            </div>
            <div class="col-10 m-0">
                <div id="grapes-js-editor">
                    <div style="padding: 15px; z-index: 9999;" data-gjs-type="editable">
                        {!! $editableObj->html !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="height: 5%;">
            <button type="button" id="gjs-update-button" class="btn btn-primary col m-2" onclick="update();">{{ __('dicms::admin.update') }}</button>
        </div>
    </div>
</div>
@push('head_scripts')
    @include('dicms::layouts.editor.config', ['objEditable' => $editableObj])
    @foreach(config('dicms.plugins') as $plugin)
        @foreach($plugin::getGrapesJsPlugins() as $editorPlugin)
            @if($editorPlugin->shouldInclude($editableObj))
                <!-- Including GrapeJs Plugin Config -->
                {!! $editorPlugin->getConfigView($editableObj) !!}
            @endif
        @endforeach
    @endforeach

    <style>
        .gjs-block {
            min-width: 45px;
            width: 45%;
            max-width: 45%;
            /* padding: 1em; */
            box-sizing: border-box;
            min-height: 90px;
            cursor: all-scroll;
            font-size: 11px;
            font-weight: lighter;
            text-align: center;
            display: flex;
            flex-direction: column;
            margin: 0;
            box-shadow: 0 1px 0 0 rgba(0, 0, 0, .15);
            transition: all .2s ease 0s;
            transition-property: box-shadow, color;
            border: 0;
        }

        #gjs-top-panel {
            padding: 0;
            width: 100%;
            display: flex;
            position: initial;
            justify-content: space-between;
            align-items: center;
        }

        #gjs-right-panel,
        #gjs-top-page-view,
        #gjs-top-device-view,
        #gjs-top-panel-switcher {
            position: initial;
        }

        .gjs-pn-buttons {
            justify-content: flex-start;
        }

        .gjs-cv-canvas {
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            bottom: 0;
            overflow: hidden;
            z-index: 1;
            position: absolute;
            left: 0;
            top: 0;
        }

        #gjs-right-panel::-webkit-scrollbar {
            width: 10px;
        }
    </style>
@endpush
@push('scripts')
    @include('dicms::layouts.editor.instance', ['objEditable' => $editableObj])
    <script>
        function update()
        {
            let payload =
                {
                    html: editor.getHtml(),
                    css: editor.getCss(),
                    data: JSON.stringify(editor.getProjectData()),
                    id: {{ $editableObj->id }},
                    objType: "{!! str_replace("\\","\\\\",$editableObj::class) !!}"
                };
            let headers =
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            axios.post('{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.content.update') }}', payload, headers)
                .then(function(response)
                {
                    if(response.data.success)
                    {
                        $('#gjs-update-button').addClass('btn-success').removeClass('btn-primary').html(response.data.success);
                    }
                    else
                    {
                        $('#gjs-update-button').addClass('btn-danger').removeClass('btn-primary').html(response.data.error);
                    }
                    setTimeout(destroyAlert, 3000);
                })
                .catch(error => console.log(error));
        }

        function destroyAlert()
        {
            $('#gjs-update-button').removeClass('btn-danger')
                .removeClass('btn-success')
                .addClass('btn-primary')
                .html('{{ __('dicms::admin.update') }}');
        }
    </script>
@endpush
