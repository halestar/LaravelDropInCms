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

    public function index(Site $site)
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
                    'link' => DiCMS::dicmsRoute('admin.pages.create', ['site' => $site->id]),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::pages.new'),
                ];
            $template['buttons']['import'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.import.show', ['site' => $site->id]),
                    'text' => '<i class="fa-solid fa-file-import"></i>',
                    'classes' => 'link-warning',
                    'title' => __('dicms::pages.import'),
                ];
        }
        return view('dicms::pages.index', compact('template', 'site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Page::class);
        $template =
            [
                'title' => __('dicms::pages.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.pages.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::pages.create', compact('template', 'site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Page::class);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'pages')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                    ],
                'title' => 'nullable',
                'slug' => 'required|max:255',
                'path' => 'nullable',
                'html' => 'nullable',
            ], $this->errors());
        $data['url'] = (isset($data['path'])? $data['path'] . "/": '' ) . $data['slug'];
        Validator::make(['url' => $data['url']],
        [
            'url' =>
                [
                    'required',
                    Rule::unique(config('dicms.table_prefix') . 'pages')
                        ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                ],
        ], $this->errors())->validate();

        $page = new Page();
        $page->fill($data);
        $page->site_id = $site->id;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id, 'site' => $site->id]))
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
        if(!$page->plugin_page && Gate::allows('preview', $page->site))
        {
            $template['buttons']['preview']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.preview', ['site' => $page->site_id, 'path' => $page->url]),
                    'text' => "<i class='fa-solid fa-eye'></i>",
                    'classes' => 'text-info',
                    'title' => __('dicms::sites.preview_site'),
                ];
        }

        if(Gate::allows('update', $page) && !$page->plugin_page)
        {
            $template['buttons']['edit']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.edit', ['site' => $page->site_id, 'page' => $page->id]),
                    'text' => "<i class='fa-solid fa-gear'></i>",
                    'classes' => 'text-primary',
                    'title' => __('dicms::pages.edit'),
                ];
            $template['buttons']['metadata']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.metadata', ['site' => $page->site_id, 'page' => $page->id]),
                    'text' => "<i class='fa-solid fa-info'></i>",
                    'classes' => 'text-primary',
                    'title' => __('dicms::pages.metadata'),
                ];
            $template['buttons']['manage'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.pages.index', ['site' => $page->site_id]),
                    'text' => '<i class="fa-solid fa-bars-progress"></i>',
                    'classes' => 'text-secondary',
                    'title' => __('dicms::sites.page_management'),
                ];
        }
        $objEditable = $page;
        //is this a plugin page? Then try to get the active site.
        if($page->plugin_page)
            $site = Site::activeSite();
        else //site is the page's site.
            $site = $page->site;
        return view('dicms::pages.show', compact('template', 'page', 'objEditable', 'site'));
    }

    public function edit(Page $page)
    {
        Gate::authorize('update', $page);
        if($page->plugin_page)
            abort(403);
        $template =
            [
                'title' => __('dicms::pages.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id, 'site' => $page->site_id]),
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
        return redirect()->back()
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function updateSettings(Request $request, Page $page)
    {
        Gate::authorize('update', $page);
        if($page->plugin_page)
            abort(403);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'pages')
                            ->where(function ($query) use ($page) { $query->where('site_id', $page->site_id); })
                            ->ignore($page)
                    ],
                'title' => 'nullable',
                'slug' => 'required|max:255',
                'path' => 'nullable',
            ], $this->errors());
        $data['url'] = (isset($data['path'])? $data['path'] . "/": '' ) . $data['slug'];
        Validator::make(['url' => $data['url']],
            [
                'url' =>
                    [
                        'required',
                        Rule::unique(config('dicms.table_prefix') . 'pages')
                            ->where(function ($query) use ($page) { $query->where('site_id', $page->site_id); })
                            ->ignore($page)
                    ],
            ], $this->errors())->validate();
        $page->fill($data);
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function publishPage(Request $request, Page $page)
    {
        Gate::authorize('publish', $page);
        if($page->plugin_page)
            abort(403);
        $page->published = true;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function unpublishPage(Request $request, Page $page)
    {
        Gate::authorize('activate', $page);
        if($page->plugin_page)
            abort(403);
        $page->published = false;
        $page->save();
        return redirect(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page->id]))
            ->with('success-status', __('dicms::pages.success.updated'));
    }

    public function duplicatePage(Request $request, Page $page)
    {
        Gate::authorize('create', Page::class);
        if($page->plugin_page)
            abort(403);
        $dupe = $page->dupe();
        return redirect(DiCMS::dicmsRoute('admin.pages.edit', ['page' => $dupe->id]))
            ->with('success-status', __('dicms::pages.success.created'));
    }

    public function destroy(Page $page)
    {
        Gate::authorize('delete', $page);
        if($page->plugin_page)
            abort(403);
        $page->delete();
        return redirect(DiCMS::dicmsRoute('admin.pages.index', ['site' => $page->site_id]));
    }

    public function editMetadata(Page $page)
    {
        Gate::authorize('update', $page);
        if($page->plugin_page)
            abort(403);
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

    public function import(Site $site)
    {
        Gate::authorize('create', Page::class);
        $template =
            [
                'title' => __('dicms::headers.import'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.headers.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::pages.import', compact('template', 'site'));
    }

    public function doImport(Request $request, Site $site)
    {
        Gate::authorize('create', Page::class);
        $data = $request->validate(
            [
                'pages' => 'required|array',
            ], $this->errors());
        foreach($data['pages'] as $page_id)
        {
            $page = Page::find($page_id);
            if($page)
                $page->dupe($site);
        }
        return redirect(DiCMS::dicmsRoute('admin.pages.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::pages.success.imported'));
    }
}
