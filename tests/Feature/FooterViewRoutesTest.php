<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_footers_index(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.footers.index', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_footers_create(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.footers.create', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_footers_edit(): void
    {
        $site = Site::first();
        $footer = Footer::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.footers.edit', ['site' => $site->id, 'footer' => $footer]));

        $response->assertStatus(200);
    }

}
