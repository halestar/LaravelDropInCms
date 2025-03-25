<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\FooterTagType;
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
            'footers' => __('dicms::errors.footers.import'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', Footer::class);
        $template =
            [
                'title' => __('dicms::footers.footer.title'),
                'buttons' => []
            ];
        if(Gate::allows('create', Footer::class))
        {
            $template['buttons']['create'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.footers.create', ['site' => $site->id]),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::footers.new'),
                ];
            $template['buttons']['import'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.footers.import.show', ['site' => $site->id]),
                    'text' => '<i class="fa-solid fa-file-import"></i>',
                    'classes' => 'link-warning',
                    'title' => __('dicms::footers.import'),
                ];
        }
        return view('dicms::footers.index', compact('template', 'site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Footer::class);
        $template =
            [
                'title' => __('dicms::footers.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.footers.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::footers.create', compact('template', 'site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Footer::class);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'footers')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); }),
                    ],
                'description' => 'nullable',
                'html' => 'nullable',
            ], $this->errors());
        $footer = new Footer();
        $footer->fill($data);
        $footer->site_id = $site->id;
        $footer->save();
        return redirect(DiCMS::dicmsRoute('admin.footers.edit', ['site' => $site->id, 'footer' => $footer->id]))
            ->with('success-status', __('dicms::footers.success.created'));
    }

    public function edit(Site $site, Footer $footer)
    {
        Gate::authorize('update', $footer);
        $template =
            [
                'title' => __('dicms::footers.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.footers.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        $objEditable = $footer;
        return view('dicms::footers.edit', compact( 'footer', 'objEditable', 'template', 'site'));
    }

    public function update(Request $request, Site $site, Footer $footer)
    {
        Gate::authorize('update', $footer);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'footers')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                            ->ignore($footer)
                    ],
                'description' => 'nullable',
            ], $this->errors());
        $footer->fill($data);
        $footer->save();
        return redirect()->back()
            ->with('success-status', __('dicms::footers.success.updated'));
    }

    public function destroy(Site $site, Footer $footer)
    {
        Gate::authorize('delete', $footer);
        $footer->delete();
        return redirect(DiCMS::dicmsRoute('admin.footers.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::footers.success.deleted'));
    }

    public function duplicate(Site $site, Footer $footer)
    {
        Gate::authorize('create', Footer::class);
        $newFooter = $footer->dupe();
        return redirect(DiCMS::dicmsRoute('admin.footers.edit', ['site' => $site->id, 'footer' => $newFooter->id]))
            ->with('success-status', __('dicms::footers.success.created'));
    }

    public function import(Site $site)
    {
        Gate::authorize('create', Footer::class);
        $template =
            [
                'title' => __('dicms::footers.import'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.footers.index', ['site' => $site->id]),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::footers.import', compact('template', 'site'));
    }

    public function doImport(Request $request, Site $site)
    {
        Gate::authorize('create', Footer::class);
        $data = $request->validate(
            [
                'footers' => 'required|array',
            ], $this->errors());
        foreach($data['footers'] as $footer_id)
        {
            $footer = Footer::find($footer_id);
            if($footer)
                $footer->dupe($site);
        }
        return redirect(DiCMS::dicmsRoute('admin.footers.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::footers.success.imported'));
    }
}
