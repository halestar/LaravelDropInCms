<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiscViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_backup_index(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.backups.index'));

        $response->assertStatus(200);
    }

    public function test_assets_index(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.pages.create'));

        $response->assertStatus(200);
    }

}
