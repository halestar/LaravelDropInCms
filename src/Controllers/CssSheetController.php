<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Menu;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CssSheetController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.sheets.name'),
        ];
    }

    public function index()
    {
        Gate::authorize('viewAny', CssSheet::class);
        $template =
            [
                'title' => trans_choice('dicms::css_sheets.sheet', 2),
                'buttons' => [],
            ];
        if(Gate::allows('create', CssSheet::class))
        {
            $template['buttons']['create']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.sheets.create'),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::css_sheets.new'),
                ];
        }
        return view('dicms::css_sheets.index', compact('template'));
    }

    public function create()
    {
        Gate::authorize('create', CssSheet::class);
        $template =
            [
                'title' => __('dicms::css_sheets.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.sheets.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::css_sheets.create', compact('template'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', CssSheet::class);
        $currentSite = Site::currentSite();
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'css_sheets',
                'description' => 'nullable',
                'type' => 'required|in:LINK,UPLOAD,TEXT',
            ], $this->errors());
        $sheet = new CssSheet();
        $sheet->fill($request->only(['name', 'description']));
        if($data['type'] == 'LINK')
        {
            $sheet->type = HeadElementType::Link;
            $sheet->href = $request->input('href', null);
            $sheet->link_type = $request->input('link_type', null);
        }
        elseif($data['type'] == 'UPLOAD')
        {

            $file = $request->file('sheet_file', null);
            $sheet->type = HeadElementType::Text;
            if($file)
                $sheet->sheet = file_get_contents($file->getRealPath());
        }
        else
        {
            $sheet->type = HeadElementType::Text;
            $sheet->sheet = $request->input('sheet', null);
        }
        $sheet->save();
        return redirect(DiCMS::dicmsRoute('admin.sheets.index'))
            ->with('success-status', __('dicms::css_sheets.success.created'));
    }

    public function edit(CssSheet $sheet)
    {
        Gate::authorize('update', $sheet);
        $template =
            [
                'title' => __('dicms::css_sheets.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.sheets.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::css_sheets.edit', compact('sheet', 'template'));
    }

    public function update(Request $request, CssSheet $sheet)
    {
        Gate::authorize('update', $sheet);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'css_sheets')->ignore($sheet)],
                'description' => 'nullable',
                'type' => 'required|in:LINK,UPLOAD,TEXT',
            ], $this->errors());
        $sheet->fill($request->only(['name', 'description']));
        if($data['type'] == 'LINK')
        {
            $sheet->type = HeadElementType::Link;
            $sheet->href = $request->input('href', null);
            $sheet->link_type = $request->input('link_type', null);
        }
        elseif($data['type'] == 'UPLOAD')
        {

            $file = $request->file('sheet_file', null);
            $sheet->type = HeadElementType::Text;
            if($file)
                $sheet->sheet = file_get_contents($file->getRealPath());
        }
        else
        {
            $sheet->type = HeadElementType::Text;
            $sheet->sheet = $request->input('sheet', null);
        }
        $sheet->save();
        return redirect(DiCMS::dicmsRoute('admin.sheets.index'))
            ->with('success-status', __('dicms::css_sheets.success.updated'));
    }

    public function destroy(CssSheet $sheet)
    {
        Gate::authorize('delete', $sheet);
        $sheet->delete();
        return redirect(DiCMS::dicmsRoute('admin.sheets.index'))
            ->with('success-status', __('dicms::css_sheets.success.deleted'));
    }

    public function duplicate(CssSheet $sheet)
    {
        Gate::authorize('create', CssSheet::class);
        $newCss = $sheet->dupe();
        return redirect(DiCMS::dicmsRoute('admin.sheets.edit', ['sheet' => $newCss->id]))
            ->with('success-status', __('dicms::css_sheets.success.created'));
    }
}
