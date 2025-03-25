<div>
    <h4 class="border-bottom d-flex justify-content-between align-items-center">
        {{ $title }}
        @if($manageLink)
            <a
                href="{{ $manageLink }}"
                class="link-secondary link-underline-opacity-0"
            ><i class="fa-solid fa-bars-progress"></i></a>
        @endif
    </h4>
    <div class="input-group" aria-describedby="jsScriptHelp">
        <label for="script_id" class="input-group-text">{{ __('dicms::sites.scripts.add') }}</label>
        <select name="script_id" id="script_id" class="form-select"
                wire:change="addJsScript($('#script_id').val())">
            @foreach($container->getJsScriptPool()->whereNotIn('id', $jsScripts->pluck('id')) as $script)
                @if($loop->first)
                    <option value="" selected>{{ __('dicms::sites.scripts.add.select') }}</option>
                @endif
                <option value="{{ $script->id }}" wire:key="{{ $script->id }}">{{ $script->name }}</option>
            @endforeach
        </select>
    </div>
    <div id="jsScriptHelp" class="form-text mb-3">{{ __('dicms::pages.scripts.add.help') }}</div>
    @if($jsScripts->count() > 0)
        <h5 class="border-bottom">{{ __('dicms::sites.scripts.assigned') }}</h5>
        <ul class="list-group" wire:sortable="updateOrder">
            @foreach($jsScripts as $js)
                <li class="list-group-item d-flex justify-content-start align-items-center" wire:key="js-script-{{ $js->id }}" wire:sortable.item="{{ $js->id }}">
                    <span class="ms-1 me-2" wire:sortable.handle><i class="fa-solid fa-grip-lines-vertical"></i></span>
                    <span class="fw-bolder fs-6">{{ $js->name }}</span>
                    <span class="ms-auto">
                        <a
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.scripts.edit', ['site' => $js->site_id, 'script' => $js->id]) }}"
                            class="btn btn-primary btn-sm me-1"
                            role="button"
                            title="{{ __('dicms::admin.edit') }}"
                        ><i class="fa fa-edit"></i></a>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            wire:click="removeJsScript({{ $js->id }})"
                            title="{{ __('dicms::admin.remove') }}"
                        ><i class="fa fa-times"></i></button>
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
