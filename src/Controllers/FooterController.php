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
        ];
    }

    public function index()
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
                    'link' => DiCMS::dicmsRoute('admin.footers.create'),
                    'text' => "<i class='fa fa-plus-square'></i>",
                    'classes' => 'bg-text-primary',
                    'title' => __('dicms::footers.new'),
                ];
        }
        return view('dicms::footers.index', compact('template'));
    }

    public function create()
    {
        Gate::authorize('create', Footer::class);
        $template =
            [
                'title' => __('dicms::footers.new'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.footers.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        return view('dicms::footers.create', compact('template'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Footer::class);
        $currentSite = Site::currentSite();
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'footers',
                'description' => 'nullable',
                'html' => 'nullable',
            ], $this->errors());
        $footer = new Footer();
        $footer->fill($data);
        $footer->save();
        return redirect(DiCMS::dicmsRoute('admin.footers.edit', ['footer' => $footer->id]))
            ->with('success-status', __('dicms::footers.success.created'));
    }

    public function edit(Footer $footer)
    {
        Gate::authorize('update', $footer);
        $template =
            [
                'title' => __('dicms::footers.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.footers.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        $objEditable = $footer;
        return view('dicms::footers.edit', compact( 'footer', 'objEditable', 'template'));
    }

    public function update(Request $request, Footer $footer)
    {
        Gate::authorize('update', $footer);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'footers')->ignore($footer)],
                'description' => 'nullable',
            ], $this->errors());
        $footer->fill($data);
        $footer->save();
        return redirect()->back()
            ->with('success-status', __('dicms::footers.success.updated'));
    }

    public function destroy(Footer $footer)
    {
        Gate::authorize('delete', $footer);
        $footer->delete();
        return redirect(DiCMS::dicmsRoute('admin.footers.index'))
            ->with('success-status', __('dicms::footers.success.deleted'));
    }

    public function duplicate(Footer $footer)
    {
        Gate::authorize('create', Footer::class);
        $newFooter = $footer->dupe();
        return redirect(DiCMS::dicmsRoute('admin.footers.edit', ['footer' => $newFooter->id]))
            ->with('success-status', __('dicms::footers.success.created'));
    }
}
