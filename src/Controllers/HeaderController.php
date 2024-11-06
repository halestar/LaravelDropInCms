<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Header;
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

    public function index()
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
                            'link' => DiCMS::dicmsRoute('admin.headers.create'),
                            'text' => "<i class='fa fa-plus-square'></i>",
                            'classes' => 'bg-text-primary',
                            'title' => __('dicms::headers.new'),
                        ];
        }
        return view('dicms::headers.index', compact('template'));
    }

    public function create()
    {
        Gate::authorize('create', Header::class);
        $template =
            [
                'title' => __('dicms::headers.new'),
                'buttons' =>
                [
                    'back' =>
                    [
                        'link' => DiCMS::dicmsRoute('admin.headers.index'),
                        'text' => '<i class="fa-solid fa-rotate-left"></i>',
                        'classes' => 'text-secondary',
                        'title' => __('dicms::admin.back'),
                    ]
                ]
            ];
        return view('dicms::headers.create', compact('template'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Header::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'headers',
                'description' => 'nullable',
            ], $this->errors());
        $header = new Header();
        $header->fill($data);
        $header->save();
        return redirect(DiCMS::dicmsRoute('admin.headers.edit', ['header' => $header->id]))
            ->with('success-status', __('dicms::headers.success.created'));
    }

    public function edit(Header $header)
    {
        Gate::authorize('update', $header);
        $template =
            [
                'title' => __('dicms::headers.edit'),
                'buttons' =>
                    [
                        'back' =>
                            [
                                'link' => DiCMS::dicmsRoute('admin.headers.index'),
                                'text' => '<i class="fa-solid fa-rotate-left"></i>',
                                'classes' => 'text-secondary',
                                'title' => __('dicms::admin.back'),
                            ]
                    ]
            ];
        $objEditable = $header;
        return view('dicms::headers.edit', compact('header', 'objEditable', 'template'));
    }

    public function update(Request $request, Header $header)
    {
        Gate::authorize('update', $header);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'headers')->ignore($header)],
                'description' => 'nullable',
            ], $this->errors());
        $header->fill($data);
        $header->save();
        return redirect()->back()
            ->with('success-status', __('dicms::headers.success.updated'));
    }

    public function destroy(Header $header)
    {
        Gate::authorize('delete', $header);
        $header->delete();
        return redirect(DiCMS::dicmsRoute('admin.headers.index'))
            ->with('success-status', __('dicms::headers.success.deleted'));
    }

    public function duplicate(Header $header)
    {
        Gate::authorize('create', Header::class);
        $newHeader = $header->dupe();
        return redirect(DiCMS::dicmsRoute('admin.headers.edit', ['header' => $newHeader->id]))
            ->with('success-status', __('dicms::headers.success.created'));
    }
}
