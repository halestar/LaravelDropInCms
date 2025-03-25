<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Menu;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JsScriptsViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_scripts_index(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.scripts.index', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_scripts_create(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.scripts.create', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_scripts_edit(): void
    {
        $site = Site::first();
        $script = JsScript::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.scripts.edit', ['site' => $site->id, 'script' => $script]));

        $response->assertStatus(200);
    }

}
