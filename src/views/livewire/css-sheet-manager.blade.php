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
    <div class="input-group" aria-describedby="cssSheetHelp">
        <label for="sheet_id" class="input-group-text">{{ __('dicms::sites.sheets.add') }}</label>
        <select name="sheet_id" id="sheet_id" class="form-select" wire:change="addCssSheet($('#sheet_id').val())">
            @foreach($container->getCssSheetPool()->whereNotIn('id', $cssSheets->pluck('id')) as $sheet)
                @if($loop->first)
                    <option value="" selected>{{ __('dicms::sites.sheets.add.select') }}</option>
                @endif
                <option value="{{ $sheet->id }}" wire:key="{{ $sheet->id }}">{{ $sheet->name }}</option>
            @endforeach
        </select>
    </div>
    <div id="cssSheetHelp" class="form-text mb-3">{{ __('dicms::sites.sheets.add.help') }}</div>
    @if($cssSheets->count() > 0)
        <h5 class="border-bottom">{{ __('dicms::sites.sheets.assigned') }}</h5>
        <ul class="list-group" wire:sortable="updateOrder">
            @foreach($cssSheets as $css)
                <li class="list-group-item d-flex justify-content-start align-items-center" wire:key="css-sheet-{{ $css->id }}" wire:sortable.item="{{ $css->id }}">
                    <span class="ms-1 me-2" wire:sortable.handle><i class="fa-solid fa-grip-lines-vertical"></i></span>
                    <span class="fw-bolder fs-6">{{ $css->name }}</span>
                    <span class="ms-auto">
                        <a
                            href="{{ \halestar\LaravelDropInCms\DiCMS::dicmsRoute('admin.sheets.edit', ['site' => $css->site_id, 'sheet' => $css->id]) }}"
                            class="btn btn-primary btn-sm me-1"
                            role="button"
                            title="{{ __('dicms::admin.edit') }}"
                        ><i class="fa fa-edit"></i></a>
                        <button
                            class="btn btn-danger btn-sm"
                            type="button"
                            title="{{ __('dicms::admin.remove') }}"
                            wire:click="removeCssSheet({{ $css->id }})"
                        ><i class="fa fa-times"></i></button>
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
