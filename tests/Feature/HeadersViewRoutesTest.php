<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Header;
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
        $response = $this->get(DiCMS::dicmsRoute('admin.headers.index'));

        $response->assertStatus(200);
    }

    public function test_headers_create(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.headers.create'));

        $response->assertStatus(200);
    }

    public function test_headers_edit(): void
    {
        $header = Header::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.headers.edit', ['header' => $header]));

        $response->assertStatus(200);
    }

}
