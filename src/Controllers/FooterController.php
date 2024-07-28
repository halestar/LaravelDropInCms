<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class FooterController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.footers.name'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', Footer::class);
        return view('dicms::footers.index', compact('site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Footer::class);
        return view('dicms::footers.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Footer::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'footers',
                'description' => 'nullable',
                'options' => 'nullable',
            ], $this->errors());
        $footer = new Footer();
        $footer->fill($data);
        $site->footers()->save($footer);
        return redirect(DiCMS::dicmsRoute('admin.sites.footers.edit', ['site' => $site->id, 'footer' => $footer->id]));
    }

    public function edit(Site $site, Footer $footer)
    {
        Gate::authorize('update', $footer);
        $editor =
            [
                'editor' => '#footer_editor',
                'styles' => '"' . $site->siteCss()->links()->get()->pluck('href')->join('","') . '","' . DiCMS::dicmsPublicCss($site) . '"',
                'scripts' => '"' . $site->siteJs()->links()->get()->pluck('href')->join('","') . '","' . DiCMS::dicmsPublicJs($site) . '"',
                'projectData' => $footer->data,
            ];
        return view('dicms::footers.edit', compact('site', 'footer', 'editor'));
    }

    public function update(Request $request, Site $site, Footer $footer)
    {
        Gate::authorize('update', $footer);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'footers')->ignore($footer)],
                'description' => 'nullable',
                'footer' => 'nullable',
            ], $this->errors());
        $footer->fill($data);
        $footer->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.footers.index', ['site' => $site->id]));
    }

    public function updateContent(Request $request, Footer $footer)
    {
        Gate::authorize('update', $footer);
        $footer->html = $request->input('footer', null);
        $footer->data = $request->input('data', null);
        $footer->css = $request->input('css', null);
        $footer->save();
        return redirect()->back();
    }

    public function destroy(Site $site, Footer $footer)
    {
        Gate::authorize('delete', $footer);
        $footer->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.footers.index', ['site' => $site->id]));
    }
}
