<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\HeadElementType;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class JsScriptController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.scripts.name'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', JsScript::class);
        return view('dicms::js_scripts.index', compact('site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', JsScript::class);
        return view('dicms::js_scripts.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', JsScript::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'js_scripts',
                'description' => 'nullable',
                'type' => 'required|in:LINK,UPLOAD,TEXT',
            ], $this->errors());
        $script = new JsScript();
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
        $site->jsScripts()->save($script);
        return redirect(DiCMS::dicmsRoute('admin.sites.scripts.index', ['site' => $site->id]));
    }

    public function edit(Site $site, JsScript $script)
    {
        Gate::authorize('update', $script);
        return view('dicms::js_scripts.edit', compact('site', 'script'));
    }

    public function update(Request $request, Site $site, JsScript $script)
    {
        Gate::authorize('update', $script);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'js_scripts')->ignore($script)],
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
        return redirect(DiCMS::dicmsRoute('admin.sites.scripts.index', ['site' => $site->id]));
    }

    public function destroy(Site $site, JsScript $script)
    {
        Gate::authorize('delete', $script);
        $script->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.scripts.index', ['site' => $site->id]));
    }
}
