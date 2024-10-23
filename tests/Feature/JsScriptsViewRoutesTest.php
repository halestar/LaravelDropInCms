<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Menu;
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
        $response = $this->get(DiCMS::dicmsRoute('admin.scripts.index'));

        $response->assertStatus(200);
    }

    public function test_scripts_create(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.scripts.create'));

        $response->assertStatus(200);
    }

    public function test_scripts_edit(): void
    {
        $script = JsScript::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.scripts.edit', ['script' => $script]));

        $response->assertStatus(200);
    }

}
