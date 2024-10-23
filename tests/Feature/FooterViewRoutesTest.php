<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Footer;
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
        $response = $this->get(DiCMS::dicmsRoute('admin.footers.index'));

        $response->assertStatus(200);
    }

    public function test_footers_create(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.footers.create'));

        $response->assertStatus(200);
    }

    public function test_footers_edit(): void
    {
        $footer = Footer::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.footers.edit', ['footer' => $footer]));

        $response->assertStatus(200);
    }

}
