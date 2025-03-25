<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_pages_index(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.index', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_pages_show(): void
    {
        $site = Site::first();
        $page = Page::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.show', ['site' => $site->id, 'page' => $page]));

        $response->assertStatus(200);
    }

    public function test_pages_create(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.create', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_pages_edit(): void
    {
        $site = Site::first();
        $page = Page::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.edit', ['site' => $site->id, 'page' => $page]));

        $response->assertStatus(200);
    }

}
