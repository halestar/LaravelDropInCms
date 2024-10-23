<?php

namespace halestar\LaravelDropInCms\Tests\Feature;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Menu;
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
        $response = $this->get(DiCMS::dicmsRoute('admin.sheets.index'));

        $response->assertStatus(200);
    }

    public function test_sheets_create(): void
    {
        $response = $this->get(DiCMS::dicmsRoute('admin.sheets.create'));

        $response->assertStatus(200);
    }

    public function test_sheets_edit(): void
    {
        $sheet = CssSheet::first();
        $response = $this->get(DiCMS::dicmsRoute('admin.sheets.edit', ['sheet' => $sheet]));

        $response->assertStatus(200);
    }

}
