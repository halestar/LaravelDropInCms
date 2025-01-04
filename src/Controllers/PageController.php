<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
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

    public function index()
    {
        Gate::authorize('viewAny', Page::class);
        $template =
            [
                'title' => __('dicms::pages.pages.title'),
                'buttons' => []
            ];
        if(Gate::allows('create', Page::class))
        {
            $template['buttons']['create'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.create'),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::pages.new'),
                ];
        }
        return view('dicms::pages.index', compact('template'));
    }

    public function create()
    {
        Gate::authorize('create', Page::class);
        $template =
            [
                'title' => __('dicms::pages.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.pages.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::pages.create', compact('template'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Page::class);
        $currentSite = Site::currentSite();
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
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.created'));
    }

    public function show(Page $page)
    {
        Gate::authorize('update', $page);
        $template =
            [
                'title' => $page->name,
                'buttons' => []
            ];
        $currentSite = Site::currentSite();
        if(Gate::allows('preview', $currentSite))
        {
            $template['buttons']['preview']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.preview.home', ['path' => $page->url]),
                    'text' => "<i class='fa-solid fa-eye'></i>",
                    'classes' => 'text-info',
                    'title' => __('dicms::sites.preview_site'),
                ];
        }
        if(Gate::allows('update', $page) && !$page->plugin_page)
        {
            $template['buttons']['edit']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.edit', ['page' => $page->id]),
                    'text' => "<i class='fa-solid fa-gear'></i>",
                    'classes' => 'text-primary',
                    'title' => __('dicms::pages.edit'),
                ];
            $template['buttons']['metadata']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.metadata', ['page' => $page->id]),
                    'text' => "<i class='fa-solid fa-info'></i>",
                    'classes' => 'text-primary',
                    'title' => __('dicms::pages.metadata'),
                ];
        }
        $template['buttons']['manage'] =
            [
                'link' => DiCMS::dicmsRoute('admin.pages.index'),
                'text' => '<i class="fa-solid fa-bars-progress"></i>',
                'classes' => 'text-secondary',
                'title' => __('dicms::sites.page_management'),
            ];
        $objEditable = $page;
        return view('dicms::pages.show', compact('template', 'page', 'objEditable'));
    }

    public function edit(Page $page)
    {
        Gate::authorize('update', $page);
        $template =
            [
                'title' => __('dicms::pages.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::pages.settings', compact('page', 'template'));
    }

    public function update(Request $request, Page $page)
    {
        Gate::authorize('update', $page);
        $data = $request->validate(
            [
                'title' => 'nullable',
                'override_css' => 'required|boolean',
                'override_js' => 'required|boolean',
                'override_header' => 'required|boolean',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'override_footer' => 'required|boolean',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
            ], $this->errors());
        $page->fill($data);
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
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
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function publishPage(Request $request, Page $page)
    {
        Gate::authorize('publish', $page);
        $page->published = true;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function unpublishPage(Request $request, Page $page)
    {
        Gate::authorize('activate', $page);
        $page->published = false;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function duplicatePage(Request $request, Page $page)
    {
        Gate::authorize('create', Page::class);
        $dupe = $page->dupe();
        return redirect(DiCMS::dicmsRoute('admin.pages.edit', ['page' => $dupe->id]))
            ->with('success-status', __('dicms::pages.success.created'));
    }

    public function destroy(Page $page)
    {
        Gate::authorize('delete', $page);
        $page->delete();
        return redirect(DiCMS::dicmsRoute('admin.pages.index'));
    }

    public function editMetadata(Page $page)
    {
        Gate::authorize('update', $page);
        $template =
            [
                'title' => __('dicms::pages.metadata'),
                'buttons' =>
                    [
                        'back'  =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        $obj = $page;
        return view('dicms::sites.metadata', compact('obj', 'template'));
    }
}
