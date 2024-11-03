<div {{ $attributes }}>
    <div class="mb-3 d-flex justify-content-between align-items-bottom border-bottom">
        <div>
            <label for="footer" class="form-label">{{ $title }}</label>
            <div id="footerHelp" class="form-text ">{{ $help }}</div>
        </div>
        <div class="alert d-none" id="result-alert"></div>
    </div>
    <div class="row gx-0 mb-5" id="gjs-container">
        <div class="col-2 overflow-scroll overflow-x-hidden" id="blocks-column" style="height: 800px">
            <div id="blocks-container"></div>
        </div>
        <div class="col-9">
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

    <div class="row">
        <button type="button" class="btn btn-primary col m-2" onclick="update();">{{ __('dicms::admin.update') }}</button>
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
                        $('#result-alert').addClass('alert-success').removeClass('d-none').html(response.data.success);
                    }
                    else
                    {
                        $('#result-alert').addClass('alert-danger').removeClass('d-none').html(response.data.error);
                    }
                    setTimeout(destroyAlert, 5000);
                })
                .catch(error => console.log(error));
        }

        function destroyAlert()
        {
            $('#result-alert').removeClass('alert-danger')
                .removeClass('alert-success')
                .addClass('d-none')
                .html('');
        }
    </script>
@endpush
