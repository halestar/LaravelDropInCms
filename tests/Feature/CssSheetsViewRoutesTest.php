<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Menu;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CssSheetsViewRoutesTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_sheets_index(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.sheets.index', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_sheets_create(): void
    {
        $site = Site::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.sheets.create', ['site' => $site->id]));

        $response->assertStatus(200);
    }

    public function test_sheets_edit(): void
    {
        $site = Site::first();
        $sheet = CssSheet::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.sheets.edit', ['site' => $site, 'sheet' => $sheet]));

        $response->assertStatus(200);
    }

}
