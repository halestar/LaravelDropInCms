<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.pages.name'),
            'slug' => __('dicms::errors.pages.slug'),
            'url' => __('dicms::errors.pages.url'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', Page::class);
        return view('dicms::pages.index', compact('site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Page::class);
        return view('dicms::pages.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Page::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'pages',
                'title' => 'nullable',
                'slug' => 'required|max:255',
                'path' => 'nullable',
            ], $this->errors());
        $data['url'] = (isset($data['path'])? $data['path'] . "/": '' ) . $data['slug'];
        Validator::make(['url' => $data['url']],
        [
            'url' => 'required|unique:' . config('dicms.table_prefix') . 'pages',
        ], $this->errors())->validate();

        $page = new Page();
        $page->fill($data);
        $site->pages()->save($page);
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $site->id, 'page' => $page->id]));
    }

    public function show(Site $site, Page $page)
    {
        $editor =
            [
                'editor' => '#page_editor',
                'styles' => '"' . $site->siteCss()->links()->get()->pluck('href')->join('","') . '","' . DiCMS::dicmsPublicCss($site) . '"',
                'scripts' => '"' . $site->siteJs()->links()->get()->pluck('href')->join('","') . '","' . DiCMS::dicmsPublicJs($site) . '"',
                'projectData' => $page->data,
            ];
        return view('dicms::pages.show', compact('site', 'page', 'editor'));
    }

    public function edit(Site $site, Page $page)
    {
        Gate::authorize('update', $page);
        $page =
            [
                'header' => '',//$page->header(),
                'body_style' => $page->site->body_style,
                'body_classes' => $page->site->body_classes,
                'page_header' => $page->pageHeader(),
                'page_footer' => $page->pageFooter(),
                'css' => $site->siteCss->pluck('sheet')->join("\n"),
                'js' => $site->siteJs->pluck('script')->join("\n"),
                'page_title' => $site->title,
                'page' => $page,
            ];
        return view('dicms::layouts.webeditor', $page);
    }

    public function update(Request $request, Site $site, Page $page)
    {
        Gate::authorize('update', $page);
        $data = $request->validate(
            [
                'title' => 'nullable',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
            ], $this->errors());
        $page->title = $data['title'];
        $page->header_id = $data['header_id'];
        $page->footer_id = $data['footer_id'];
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $site->id, 'page' => $page->id]));
    }

    public function editSettings(Page $page)
    {
        Gate::authorize('update', $page);
        return view('dicms::pages.settings', compact('page'));
    }

    public function updateSettings(Request $request, Page $page)
    {
        Gate::authorize('update', $page);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'pages')->ignore($page)],
                'title' => 'nullable',
                'slug' => 'required|max:255',
                'path' => 'nullable',
            ], $this->errors());
        $data['url'] = (isset($data['path'])? $data['path'] . "/": '' ) . $data['slug'];
        Validator::make(['url' => $data['url']],
            [
                'url' => ['required', Rule::unique(config('dicms.table_prefix') . 'pages')->ignore($page)],
            ], $this->errors())->validate();
        $page->fill($data);
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));
    }

    public function updateContent(Request $request, Page $page)
    {
        Gate::authorize('update', $page);
        $page->html = $request->input('page', null);
        $page->data = $request->input('data', null);
        $page->css = $request->input('css', null);
        $page->save();
        return redirect()->back();
    }

    public function publishPage(Request $request, Page $page)
    {
        Gate::authorize('publish', $page);
        $page->published = true;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));
    }

    public function unpublishPage(Request $request, Page $page)
    {
        Gate::authorize('activate', $page);
        $page->published = false;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));

    }

    public function addCss(Request $request, Page $page)
    {
        Gate::authorize('update', $page);
        $sheet_id = $request->input('sheet_id', 0);
        try
        {
            $cssSheet = CssSheet::findOrFail($sheet_id);
        }
        catch (ModelNotFoundException $e)
        {
            return redirect()->back();
        }
        $order = $page->pageCss()->count();
        $page->pageCss()->attach($cssSheet->id, ['order_by' => $order]);
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));
    }

    public function removeCss(Request $request, Page $page, CssSheet $cssSheet)
    {
        Gate::authorize('update', $page);
        $page->pageCss()->detach($cssSheet->id);
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));
    }

    public function addJs(Request $request, Page $page)
    {
        Gate::authorize('update', $page);
        $script_id = $request->input('script_id', 0);
        try
        {
            $jsScript = JsScript::findOrFail($script_id);
        }
        catch (ModelNotFoundException $e)
        {
            return redirect()->back();
        }
        $order = $page->pageJs()->count();
        $page->pageJs()->attach($jsScript->id, ['order_by' => $order]);
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));
    }

    public function removeJs(Request $request, Page $page, JsScript $jsScript)
    {
        Gate::authorize('update', $page);
        $page->pageJs()->detach($jsScript->id);
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.show', ['site' => $page->site_id, 'page' => $page->id]));
    }

    public function destroy(Site $site, Page $page)
    {
        Gate::authorize('delete', $page);
        $page->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.pages.index', ['site' => $site->id]));
    }
}
