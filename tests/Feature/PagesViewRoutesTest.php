<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Page;
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
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.index'));

        $response->assertStatus(200);
    }

    public function test_pages_show(): void
    {
        $page = Page::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.show', ['page' => $page]));

        $response->assertStatus(200);
    }

    public function test_pages_create(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.create'));

        $response->assertStatus(200);
    }

    public function test_pages_edit(): void
    {
        $page = Page::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.edit', ['page' => $page]));

        $response->assertStatus(200);
    }

}
