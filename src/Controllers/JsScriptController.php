<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class JsScriptController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.scripts.name'),
            'scripts' => __('dicms::errors.scripts.import'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', JsScript::class);
        $template =
            [
                'title' => trans_choice('dicms::js_scripts.script', 2),
                'buttons' => [],
            ];
        if(Gate::allows('create', JsScript::class))
        {
            $template['buttons']['create']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.scripts.create', ['site' => $site->id]),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::js_scripts.new'),
                ];
            $template['buttons']['import'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.scripts.import.show', ['site' => $site->id]),
                    'text' => '<i class="fa-solid fa-file-import"></i>',
                    'classes' => 'link-warning',
                    'title' => __('dicms::js_scripts.import'),
                ];
        }
        return view('dicms::js_scripts.index', compact('template', 'site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', JsScript::class);
        $template =
            [
                'title' => __('dicms::js_scripts.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::js_scripts.create', compact('template', 'site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', JsScript::class);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'js_scripts')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                    ],
                'description' => 'nullable',
                'type' => 'required|in:LINK,UPLOAD,TEXT',
            ], $this->errors());
        $script = new JsScript();
        $script->fill($request->only(['name', 'description']));
        $script->site_id = $site->id;
        if($data['type'] == 'LINK')
        {
            $script->type = HeadElementType::Link;
            $script->href = $request->input('href', null);
            $script->link_type = $request->input('link_type', null);
        }
        elseif($data['type'] == 'UPLOAD')
        {

            $file = $request->file('script_file', null);
            $script->type = HeadElementType::Text;
            if($file)
                $script->script = file_get_contents($file->getRealPath());
        }
        else
        {
            $script->type = HeadElementType::Text;
            $script->script = $request->input('script', null);
        }
        $script->save();
        return redirect(DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::js_scripts.success.created'));
    }

    public function edit(Site $site, JsScript $script)
    {
        Gate::authorize('update', $script);
        $template =
            [
                'title' => __('dicms::js_scripts.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::js_scripts.edit', compact('template', 'script', 'site'));
    }

    public function update(Request $request, Site $site, JsScript $script)
    {
        Gate::authorize('update', $script);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'js_scripts')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                            ->ignore($script)
                    ],
                'description' => 'nullable',
                'type' => 'required|in:LINK,UPLOAD,TEXT',
            ], $this->errors());
        $script->fill($request->only(['name', 'description']));
        if($data['type'] == 'LINK')
        {
            $script->type = HeadElementType::Link;
            $script->href = $request->input('href', null);
            $script->link_type = $request->input('link_type', null);
        }
        elseif($data['type'] == 'UPLOAD')
        {

            $file = $request->file('script_file', null);
            $script->type = HeadElementType::Text;
            if($file)
                $script->script = file_get_contents($file->getRealPath());
        }
        else
        {
            $script->type = HeadElementType::Text;
            $script->script = $request->input('script', null);
        }
        $script->save();
        return redirect(DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::js_scripts.success.updated'));
    }

    public function destroy(Site $site, JsScript $script)
    {
        Gate::authorize('delete', $script);
        $script->delete();
        return redirect(DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::js_scripts.success.deleted'));
    }

    public function duplicate(Site $site, JsScript $script)
    {
        Gate::authorize('create', JsScript::class);
        $newJs = $script->dupe();
        return redirect(DiCMS::dicmsRoute('admin.scripts.edit', ['script' => $newJs->id, 'site' => $site->id]))
            ->with('success-status', __('dicms::js_scripts.success.created'));
    }
    public function import(Site $site)
    {
        Gate::authorize('create', JsScript::class);
        $template =
            [
                'title' => __('dicms::js_scripts.import'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::js_scripts.import', compact('template', 'site'));
    }

    public function doImport(Request $request, Site $site)
    {
        Gate::authorize('create', JsScript::class);
        $data = $request->validate(
            [
                'scripts' => 'required|array',
            ], $this->errors());
        foreach($data['scripts'] as $script_id)
        {
            $script = JsScript::find($script_id);
            if($script)
                $script->dupe($site);
        }
        return redirect(DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::js_scripts.success.imported'));
    }
}
