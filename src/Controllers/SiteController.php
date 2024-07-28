<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SiteController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.sites.name'),
        ];
    }

    public function index()
    {
        Gate::authorize('viewAny', Site::class);
        return view('dicms::sites.index');
    }

    public function create()
    {
        Gate::authorize('create', Site::class);
        return view('dicms::sites.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Site::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255',
                'title' => 'nullable',
            ], $this->errors());
        $site = new Site();
        $site->fill($data);
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function show(Site $site)
    {
        Gate::authorize('view', $site);
        return view('dicms::sites.show', compact('site'));
    }

    public function edit(Site $site)
    {
        return view('dicms::sites.settings', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        Gate::authorize('update', $site);
        $data = $request->validate(
            [
                'title' => 'nullable',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
                'homepage_url' => 'nullable',
                'menu_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'menus,id',
            ], $this->errors());
        $site->title = $data['title'];
        $site->header_id = $data['header_id'];
        $site->footer_id = $data['footer_id'];
        $site->homepage_url = $data['homepage_url'];
        $site->menu_id = $data['menu_id'];
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function updateSettings(Request $request, Site $site)
    {
        Gate::authorize('update', $site);
        $data = $request->validate(
            [
                'title' => 'nullable',
                'body_styles' => 'nullable',
                'body_classes' => 'nullable',
                'name' => 'required|max:255',
            ], $this->errors());
        $site->title = $data['title'];
        $site->body_styles = $data['body_styles'];
        $site->body_classes = $data['body_classes'];
        $site->name = $data['name'];
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function enableSite(Request $request, Site $site)
    {
        Gate::authorize('activate', $site);
        Site::where('active', true)->update(['active' => false]);
        $site->active = true;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function disableSite(Request $request, Site $site)
    {
        Gate::authorize('activate', $site);
        $site->active = false;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));

    }

    public function archiveSite(Request $request, Site $site)
    {
        Gate::authorize('archive', $site);
        $site->archive = true;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.index'));
    }

    public function restoreSite(Request $request, Site $site)
    {
        Gate::authorize('archive', $site);
        $site->archive = false;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.index'));
    }

    public function addCss(Request $request, Site $site)
    {
        Gate::authorize('update', $site);
        $sheet_id = $request->input('sheet_id', 0);
        try
        {
            $cssSheet = CssSheet::findOrFail($sheet_id);
        }
        catch (ModelNotFoundException $e)
        {
            return redirect()->back();
        }
        $order = $site->siteCss()->count();
        $site->siteCss()->attach($cssSheet->id, ['order_by' => $order]);
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function removeCss(Request $request, Site $site, CssSheet $cssSheet)
    {
        Gate::authorize('update', $site);
        $site->siteCss()->detach($cssSheet->id);
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function addJs(Request $request, Site $site)
    {
        Gate::authorize('update', $site);
        $script_id = $request->input('script_id', 0);
        try
        {
            $jsScript = JsScript::findOrFail($script_id);
        }
        catch (ModelNotFoundException $e)
        {
            return redirect()->back();
        }
        $order = $site->siteJs()->count();
        $site->siteJs()->attach($jsScript->id, ['order_by' => $order]);
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function removeJs(Request $request, Site $site, JsScript $jsScript)
    {
        Gate::authorize('update', $site);
        $site->siteJs()->detach($jsScript->id);
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }
}
