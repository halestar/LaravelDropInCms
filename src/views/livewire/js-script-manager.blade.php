<div>
    <h4 class="border-bottom mt-5">{{ __('dicms::sites.scripts') }}</h4>
    <div class="input-group" aria-describedby="jsScriptHelp">
        <label for="script_id" class="input-group-text">{{ __('dicms::sites.scripts.add') }}</label>
        <select name="script_id" id="script_id" class="form-select"
                wire:change="addJsScript($('#script_id').val())">
            <option value="">{{ __('dicms::sites.scripts.add.select') }}</option>
            @foreach(\halestar\LaravelDropInCms\Models\JsScript::where('site_id', $siteId)->whereNotIn('id', $jsScripts->pluck('id'))->get() as $script)
                <option value="{{ $script->id }}">{{ $script->name }}</option>
            @endforeach
        </select>
        @can('viewAny', \halestar\LaravelDropInCms\Models\JsScript::class)
            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.index', ['site' => $siteId]) }}"
               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_js_scripts') }}</a>
        @endcan
    </div>
    <div id="jsScriptHelp" class="form-text mb-3">{{ __('dicms::pages.scripts.add.help') }}</div>
    @if($jsScripts->count() > 0)
        <h5 class="border-bottom">{{ __('dicms::sites.scripts.assigned') }}</h5>
        <ul class="list-group" wire:sortable="updateOrder">
            @foreach($jsScripts as $js)
                <li class="list-group-item d-flex justify-content-start align-items-center" wire:key="js-script-{{ $js->id }}" wire:sortable.item="{{ $js->id }}">
                    <span class="ms-1 me-2" wire:sortable.handle><i class="fa-solid fa-grip-lines-vertical"></i></span>
                    {{ $js->name }}
                    <span class="ms-auto">
                        <a
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.scripts.edit', ['site' => $siteId, 'script' => $js->id]) }}"
                            class="btn btn-primary btn-sm me-1"
                            role="button"
                        >{{ __('dicms::admin.edit') }}</a>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            wire:click="removeJsScript({{ $js->id }})"
                        >{{ __('dicms::admin.remove') }}</button>
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
