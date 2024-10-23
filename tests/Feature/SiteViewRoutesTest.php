<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_sites_index(): void
    {

        $response = $this->get(DiCMS::dicmsRoute('admin.sites.index'));

        $response->assertStatus(200);
    }

    public function test_sites_show(): void
    {
        $site = Site::currentSite();
        $response = $this->get(DiCMS::dicmsRoute('admin.sites.show', ['site' => $site]));

        $response->assertStatus(200);
    }

    public function test_sites_create(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.sites.create'));

        $response->assertStatus(200);
    }

    public function test_sites_edit(): void
    {
        $site = Site::currentSite();
        $response = $this->get(DiCMS::dicmsRoute('admin.sites.edit', ['site' => $site]));

        $response->assertStatus(200);
    }

}
