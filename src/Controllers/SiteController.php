<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Enums\WrapperTagType;
use halestar\LaravelDropInCms\Models\Scopes\AvailableOnlyScope;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

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
        $template =
            [
                'title' => __('dicms::sites.sites_title'),
                'buttons' => []
            ];
        if(Gate::allows('create', Site::class))
        {
            $template['buttons']['create']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.sites.create'),
                    'text' => "<i class='fa-solid fa-plus-square'></i>",
                    'classes' => 'text-primary',
                    'title' => __('dicms::sites.preview_site'),
                ];
        }
        return view('dicms::sites.index', compact('template'));
    }

    public function create()
    {
        Gate::authorize('create', Site::class);
        $currentSite = Site::currentSite();
        $template =
            [
                'title' => __('dicms::sites.new_site'),
                'buttons' => []
            ];
        if($currentSite)
        {
            $template['buttons']['back']  =
                    [
                        'link' => DiCMS::dicmsRoute('admin.sites.index'),
                        'text' => '<i class="fa-solid fa-rotate-left"></i>',
                        'classes' => 'text-secondary',
                        'title' => __('dicms::admin.back'),
                    ];
        }
        return view('dicms::sites.create', compact('template'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Site::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255',
                'title' => 'nullable',
                'description' => 'nullable',
            ], $this->errors());
        $site = new Site();
        $site->fill($data);
        $site->save();
        //make this the now current site.
        $site->makeCurrent();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]))
            ->with('success-status', __('dicms::sites.success.created'));
    }

    public function show(?Site $site = null)
    {
        if(!$site)
            $site = Site::currentSite();
        Gate::authorize('view', $site);
        $template =
            [
                'title' => "",
                'buttons' => []
            ];
        if(Gate::allows('preview', $site))
        {
            $template['buttons']['preview']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.preview.home'),
                    'text' => "<i class='fa-solid fa-eye'></i>",
                    'classes' => 'text-info',
                    'title' => __('dicms::sites.preview_site'),
                ];
        }
        if(Gate::allows('update', $site))
        {
            $template['buttons']['edit']  =
                [
                    'link' => DiCMS::dicmsRoute('admin.sites.edit', ['site' => $site->id]),
                    'text' => "<i class='fa-solid fa-gear'></i>",
                    'classes' => 'text-primary',
                    'title' => __('dicms::sites.edit_site'),
                ];
        }
        return view('dicms::sites.show', compact('site', 'template'));
    }

    public function edit(Site $site)
    {
        Gate::authorize('update', $site);
        $template =
            [
                'title' => __('dicms::sites.site.settings'),
                'buttons' =>
                    [
                        'back'  =>
                        [
                            'link' => DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]),
                            'text' => '<i class="fa-solid fa-rotate-left"></i>',
                            'classes' => 'text-secondary',
                            'title' => __('dicms::admin.back'),
                        ]
                    ],
            ];
        return view('dicms::sites.settings', compact('site', 'template'));
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
            ], $this->errors());
        $site->title = $data['title'];
        $site->header_id = $data['header_id'];
        $site->footer_id = $data['footer_id'];
        $site->homepage_url = $data['homepage_url'];
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]))
            ->with('success-status', __('dicms::sites.success.updated'));
    }

    public function updateSettings(Request $request, Site $site)
    {
        Gate::authorize('update', $site);
        $data = $request->validate(
            [
                'title' => 'nullable',
                'description' => 'nullable',
                'name' => 'required|max:255',
                'body_attr' => 'nullable',
                'favicon' => 'nullable',
                'has_wrapper' => 'boolean',
                'tag' => ['exclude_unless:has_wrapper,true', Rule::in(WrapperTagType::values())],
                'options' => 'nullable',
            ], $this->errors());
        if(!isset($data['has_wrapper']))
            $data['tag'] = null;
        $site->fill($data);
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]))
            ->with('success-status', __('dicms::sites.success.settings'));
    }

    public function enableSite(Request $request, Site $site)
    {
        Gate::authorize('activate', $site);
        Site::where('active', true)->update(['active' => false]);
        $site->active = true;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]))
            ->with('success-status', __('dicms::sites.success.enabled'));
    }

    public function disableSite(Request $request, Site $site)
    {
        Gate::authorize('activate', $site);
        $site->active = false;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]))
            ->with('success-status', __('dicms::sites.success.disabled'));

    }

    public function archiveSite(Request $request, Site $site)
    {
        Gate::authorize('archive', $site);
        $site->archived = true;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.index'))
            ->with('success-status', __('dicms::sites.success.archived'));
    }

    public function restoreSite(Request $request, $site)
    {
        $site = Site::withoutGlobalScope(AvailableOnlyScope::class)->find($site);
        Gate::authorize('archive', $site);
        $site->archived = false;
        $site->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.index'))
            ->with('success-status', __('dicms::sites.success.restored'));
    }

    public function currentSite(Request $request, Site $site)
    {
        Gate::authorize('view', $site);
        $site->makeCurrent();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site->id]));
    }

    public function duplicateSite(Site $site)
    {
        Gate::authorize('create', Site::class);
        $newSite = $site->dupe();
        return redirect(DiCMS::dicmsRoute('admin.sites.show', ['site' => $newSite->id]));
    }

    public function destroy(Site $site)
    {
        Gate::authorize('delete', Site::class);
        $site->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.index'));
    }
}
