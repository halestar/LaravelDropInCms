<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Header;
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

    public function index(Site $site)
    {
        Gate::authorize('viewAny', CssSheet::class);
        return view('dicms::css_sheets.index', compact('site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', CssSheet::class);
        return view('dicms::css_sheets.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', CssSheet::class);
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
        $site->cssSheets()->save($sheet);
        return redirect(DiCMS::dicmsRoute('admin.sites.sheets.index', ['site' => $site->id]));
    }

    public function edit(Site $site, CssSheet $sheet)
    {
        Gate::authorize('update', $sheet);
        return view('dicms::css_sheets.edit', compact('site', 'sheet'));
    }

    public function update(Request $request, Site $site, CssSheet $sheet)
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
        return redirect(DiCMS::dicmsRoute('admin.sites.sheets.index', ['site' => $site->id]));
    }

    public function destroy(Site $site, CssSheet $sheet)
    {
        Gate::authorize('delete', $sheet);
        $sheet->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.sheets.index', ['site' => $site->id]));
    }
}
