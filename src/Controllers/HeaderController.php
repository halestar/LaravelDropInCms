<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class HeaderController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.posts.name'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', Header::class);
        return view('dicms::headers.index', compact('site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Header::class);
        return view('dicms::headers.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Header::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'headers',
                'description' => 'nullable',
                'options' => 'nullable',
            ], $this->errors());
        $header = new Header();
        $header->fill($data);
        $site->headers()->save($header);
        return redirect(DiCMS::dicmsRoute('admin.sites.headers.edit', ['site' => $site->id, 'header' => $header->id]));
    }

    public function edit(Site $site, Header $header)
    {
        Gate::authorize('update', $header);
        $editor =
            [
                'editor' => '#header_editor',
                'styles' => '"' . $site->siteCss()->links()->get()->pluck('href')->join('","') . '","' . DiCMS::dicmsPublicCss($site) . '"',
                'scripts' => '"' . $site->siteJs()->links()->get()->pluck('href')->join('","') . '","' . DiCMS::dicmsPublicJs($site) . '"',
                'projectData' => $header->data,
            ];
        return view('dicms::headers.edit', compact('site', 'header', 'editor'));
    }

    public function update(Request $request, Site $site, Header $header)
    {
        Gate::authorize('update', $header);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'headers')->ignore($header)],
                'description' => 'nullable',
                'options' => 'nullable',
            ], $this->errors());
        $header->fill($data);
        $header->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.headers.index', ['site' => $site->id]));
    }

    public function updateContent(Request $request, Header $header)
    {
        Gate::authorize('update', $header);
        $header->html = $request->input('header', null);
        $header->data = $request->input('data', null);
        $header->css = $request->input('css', null);
        $header->save();
        return redirect()->back();
    }

    public function destroy(Site $site, Header $header)
    {
        Gate::authorize('delete', $header);
        $header->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.headers.index', ['site' => $site->id]));
    }
}
