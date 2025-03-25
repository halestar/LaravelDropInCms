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
            'sheets' => __('dicms::errors.sheets.import'),
        ];
    }

    public function index(Site $site)
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
                    'link' => DiCMS::dicmsRoute('admin.sheets.create', ['site' => $site->id]),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::css_sheets.new'),
                ];
            $template['buttons']['import'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.sheets.import.show', ['site' => $site->id]),
                    'text' => '<i class="fa-solid fa-file-import"></i>',
                    'classes' => 'link-warning',
                    'title' => __('dicms::css_sheets.import'),
                ];
        }
        return view('dicms::css_sheets.index', compact('template', 'site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', CssSheet::class);
        $template =
            [
                'title' => __('dicms::css_sheets.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::css_sheets.create', compact('template', 'site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', CssSheet::class);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'css_sheets')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                    ],
                'description' => 'nullable',
                'type' => 'required|in:LINK,UPLOAD,TEXT',
            ], $this->errors());
        $sheet = new CssSheet();
        $sheet->fill($request->only(['name', 'description']));
        $sheet->site_id = $site->id;
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
        return redirect(DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::css_sheets.success.created'));
    }

    public function edit(Site $site, CssSheet $sheet)
    {
        Gate::authorize('update', $sheet);
        $template =
            [
                'title' => __('dicms::css_sheets.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::css_sheets.edit', compact('sheet', 'template', 'site'));
    }

    public function update(Request $request, Site $site, CssSheet $sheet)
    {
        Gate::authorize('update', $sheet);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'css_sheets')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                            ->ignore($sheet)
                    ],
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
        return redirect(DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::css_sheets.success.updated'));
    }

    public function destroy(Site $site, CssSheet $sheet)
    {
        Gate::authorize('delete', $sheet);
        $sheet->delete();
        return redirect(DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::css_sheets.success.deleted'));
    }

    public function duplicate(Site $site, CssSheet $sheet)
    {
        Gate::authorize('create', CssSheet::class);
        $newCss = $sheet->dupe();
        return redirect(DiCMS::dicmsRoute('admin.sheets.edit', ['sheet' => $newCss->id, 'site' => $site->id]))
            ->with('success-status', __('dicms::css_sheets.success.created'));
    }

    public function import(Site $site)
    {
        Gate::authorize('create', CssSheet::class);
        $template =
            [
                'title' => __('dicms::css_sheets.import'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::css_sheets.import', compact('template', 'site'));
    }

    public function doImport(Request $request, Site $site)
    {
        Gate::authorize('create', CssSheet::class);
        $data = $request->validate(
            [
                'sheets' => 'required|array',
            ], $this->errors());
        foreach($data['sheets'] as $sheet_id)
        {
            $sheet = CssSheet::find($sheet_id);
            if($sheet)
                $sheet->dupe($site);
        }
        return redirect(DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::css_sheets.success.imported'));
    }
}
