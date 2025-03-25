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
            'headers' => __('dicms::errors.headers.import'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', Header::class);
        $template =
            [
                'title' => __('dicms::headers.headers_title'),
                'buttons' => []
            ];
        if(Gate::allows('create', Header::class))
        {
            $template['buttons']['create'] =
                        [
                            'link' => DiCMS::dicmsRoute('admin.headers.create', ['site' => $site->id]),
                            'text' => "<i class='fa fa-plus-square'></i>",
                            'classes' => 'link-primary',
                            'title' => __('dicms::headers.new'),
                        ];
            $template['buttons']['import'] =
                [
                    'link' => DiCMS::dicmsRoute('admin.headers.import.show', ['site' => $site->id]),
                    'text' => '<i class="fa-solid fa-file-import"></i>',
                    'classes' => 'link-warning',
                    'title' => __('dicms::headers.import'),
                ];
        }
        return view('dicms::headers.index', compact('template', 'site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Header::class);
        $template =
            [
                'title' => __('dicms::headers.new'),
                'buttons' =>
                [
                    'back' =>
                    [
                        'link' => DiCMS::dicmsRoute('admin.headers.index', ['site' => $site]),
                        'text' => '<i class="fa-solid fa-rotate-left"></i>',
                        'classes' => 'text-secondary',
                        'title' => __('dicms::admin.back'),
                    ]
                ]
            ];
        return view('dicms::headers.create', compact('template', 'site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Header::class);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'headers')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); }),
                    ],
                'description' => 'nullable',
                'html' => 'nullable',
            ], $this->errors());
        $header = new Header();
        $header->fill($data);
        $header->site_id = $site->id;
        $header->save();
        return redirect(DiCMS::dicmsRoute('admin.headers.edit', ['header' => $header->id, 'site' => $site->id]))
            ->with('success-status', __('dicms::headers.success.created'));
    }

    public function edit(Site $site, Header $header)
    {
        Gate::authorize('update', $header);
        $template =
            [
                'title' => __('dicms::headers.edit'),
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
        $objEditable = $header;
        return view('dicms::headers.edit', compact('header', 'objEditable', 'template', 'site'));
    }

    public function update(Request $request, Site $site, Header $header)
    {
        Gate::authorize('update', $header);
        $data = $request->validate(
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'headers')
                            ->where(function ($query) use ($site) { $query->where('site_id', $site->id); })
                            ->ignore($header)
                    ],
                'description' => 'nullable',
            ], $this->errors());
        $header->fill($data);
        $header->save();
        return redirect()->back()
            ->with('success-status', __('dicms::headers.success.updated'));
    }

    public function destroy(Site $site, Header $header)
    {
        Gate::authorize('delete', $header);
        $header->delete();
        return redirect(DiCMS::dicmsRoute('admin.headers.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::headers.success.deleted'));
    }

    public function duplicate(Site $site, Header $header)
    {
        Gate::authorize('create', Header::class);
        $newHeader = $header->dupe();
        return redirect(DiCMS::dicmsRoute('admin.headers.edit', ['header' => $newHeader->id, 'site' => $site->id]))
            ->with('success-status', __('dicms::headers.success.created'));
    }

    public function import(Site $site)
    {
        Gate::authorize('create', Header::class);
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
        return view('dicms::headers.import', compact('template', 'site'));
    }

    public function doImport(Request $request, Site $site)
    {
        Gate::authorize('create', Header::class);
        $data = $request->validate(
            [
                'headers' => 'required|array',
            ], $this->errors());
        foreach($data['headers'] as $header_id)
        {
            $header = Header::find($header_id);
            if($header)
                $header->dupe($site);
        }
        return redirect(DiCMS::dicmsRoute('admin.headers.index', ['site' => $site->id]))
            ->with('success-status', __('dicms::headers.success.imported'));
    }
}
