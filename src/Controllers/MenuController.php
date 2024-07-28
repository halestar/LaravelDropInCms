<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Menu;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MenuController
{
    private function errors(): array
    {
        return
        [
            'name' => __('dicms::errors.menus.name'),
        ];
    }

    public function index(Site $site)
    {
        Gate::authorize('viewAny', Menu::class);
        return view('dicms::menus.index', compact('site'));
    }

    public function create(Site $site)
    {
        Gate::authorize('create', Menu::class);
        return view('dicms::menus.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        Gate::authorize('create', Menu::class);
        $data = $request->validate(
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'menus',
                'description' => 'nullable',
            ], $this->errors());
        $menu = new Menu();
        $menu->fill($data);
        $site->menus()->save($menu);
        return redirect(DiCMS::dicmsRoute('admin.sites.menus.edit', ['site' => $site->id, 'menu' => $menu->id]));
    }

    public function edit(Site $site, Menu $menu)
    {
        Gate::authorize('update', $menu);

        return view('dicms::menus.edit', compact('site', 'menu'));
    }

    public function update(Request $request, Site $site, Menu $menu)
    {
        Gate::authorize('update', $menu);
        $data = $request->validate(
            [
                'name' => ['required', 'max:255', Rule::unique(config('dicms.table_prefix') . 'menus')->ignore($menu)],
                'description' => 'nullable',
                'nav_classes' => 'nullable',
                'container_classes' => 'nullable',
                'element_classes' => 'nullable',
                'link_classes' => 'nullable',
            ], $this->errors());
        $menu->fill($data);
        $menu->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.menus.edit', ['site' => $site->id, 'menu' => $menu->id]));
    }

    public function updateContent(Request $request, Menu $menu)
    {
        Gate::authorize('update', $menu);
        $data = $request->validate(
            [
                'menu' => 'nullable',
            ], $this->errors());
        $menu->menu = json_decode($data['menu'], true);
        $menu->save();
        return redirect(DiCMS::dicmsRoute('admin.sites.menus.edit', ['site' => $menu->site_id, 'menu' => $menu->id]));
    }


    public function destroy(Site $site, Menu $menu)
    {
        Gate::authorize('delete', $menu);
        $menu->delete();
        return redirect(DiCMS::dicmsRoute('admin.sites.menus.index', ['site' => $site->id]));
    }
}
