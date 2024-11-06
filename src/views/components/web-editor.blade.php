<div {{ $attributes }}>
    <div class="mb-3 d-flex justify-content-between align-items-bottom border-bottom">
        <div>
            <label for="footer" class="form-label">{{ $title }}</label>
            <div id="footerHelp" class="form-text ">{{ $help }}</div>
        </div>
    </div>
    <div id="gjs-container" style="height: 800px;">
        <div class="row gx-0" style="height: 95%;">
            <div class="col-2 overflow-scroll overflow-x-hidden me-0" id="blocks-column" style="height: 100%;">
                <div id="blocks-container"></div>
            </div>
            <div class="col-10 m-0">
                <div id="grapes-js-editor">
                    <div style="padding: 15px; z-index: 9999;" data-gjs-type="editable">
                        {{ $editableObj->html }}
                    </div>
                </div>
            </div>
            <div class="col-1">
                <div id="devices-container"></div>
            </div>
        </div>
        <div class="row" style="height: 5%;">
            <button type="button" id="gjs-update-button" class="btn btn-primary col m-2" onclick="update();">{{ __('dicms::admin.update') }}</button>
        </div>
    </div>


</div>
@push('head_scripts')
    <!-- Including GrapeJs Base Config -->
    @include('dicms::layouts.editor.config', ['objEditable' => $editableObj])

    @foreach(config('dicms.plugins') as $plugin)
        @foreach($plugin::getGrapesJsPlugins() as $editorPlugin)
            @if($editorPlugin->shouldInclude($editableObj))
                <!-- Including GrapeJs Plugin Config -->
                {!! $editorPlugin->getConfigView($editableObj) !!}
            @endif
        @endforeach
    @endforeach
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
