<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeadersViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_headers_index(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.headers.index', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_headers_create(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.headers.create', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_headers_edit(): void
    {
        $site = Site::first();
        $header = Header::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.headers.edit', ['site' => $site->id, 'header' => $header]));

        $response->assertStatus(200);
    }

}
