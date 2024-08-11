<div>
    <h4 class="border-bottom">{{ __('dicms::sites.sheets') }}</h4>
    <div class="input-group" aria-describedby="cssSheetHelp">
        <label for="sheet_id" class="input-group-text">{{ __('dicms::sites.sheets.add') }}</label>
        <select name="sheet_id" id="sheet_id" class="form-select" wire:change="addCssSheet($('#sheet_id').val())">
            <option value="">{{ __('dicms::sites.sheets.add.select') }}</option>
            @foreach(\halestar\LaravelDropInCms\Models\CssSheet::where('site_id', $siteId)->whereNotIn('id', $cssSheets->pluck('id'))->get() as $sheet)
                <option value="{{ $sheet->id }}">{{ $sheet->name }}</option>
            @endforeach
        </select>
        @can('viewAny', \halestar\LaravelDropInCms\Models\CssSheet::class)
            <a href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.sheets.index', ['site' => $siteId]) }}"
               class="btn btn-outline-secondary">{{ __('dicms::admin.manage_css_sheets') }}</a>
        @endcan
    </div>
    <div id="cssSheetHelp" class="form-text mb-3">{{ __('dicms::sites.sheets.add.help') }}</div>
    @if($cssSheets->count() > 0)
        <h5 class="border-bottom">{{ __('dicms::sites.sheets.assigned') }}</h5>
        <ul class="list-group" wire:sortable="updateOrder">
            @foreach($cssSheets as $css)
                <li class="list-group-item d-flex justify-content-start align-items-center" wire:key="css-sheet-{{ $css->id }}" wire:sortable.item="{{ $css->id }}">
                    <span class="ms-1 me-2" wire:sortable.handle><i class="fa-solid fa-grip-lines-vertical"></i></span>
                    {{ $css->name }}
                    <span class="ms-auto">
                        <a
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sites.sheets.edit', ['site' => $siteId, 'sheet' => $css->id]) }}"
                            class="btn btn-primary btn-sm me-1"
                            role="button"
                        >{{ __('dicms::admin.edit') }}</a>
                        <button
                            class="btn btn-danger btn-sm"
                            role="button"
                            wire:click="removeCssSheet({{ $css->id }})"
                        >{{ __('dicms::admin.remove') }}</button>
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
