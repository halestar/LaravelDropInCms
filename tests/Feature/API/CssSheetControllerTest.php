<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CssSheetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.sheets.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        '*' =>
                        [
                            'id',
                            'type',
                            'name',
                            'description',
                            'sheet',
                            'href',
                            'link_type',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    public function test_css_sheet_link_is_created_successfully()
    {
        $payload =
            [
                'type' => 'Link',
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'sheet' => null,
                'href' => $this->faker->url,
                'link_type' => 'rel="stylesheet"',
            ];
        $this->postJson(DiCMS::dicmsRoute('api.sheets.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'type',
                            'name',
                            'description',
                            'sheet',
                            'href',
                            'link_type',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'css_sheets', $payload);
    }

    public function test_css_sheet_text_is_created_successfully()
    {
        $payload =
            [
                'type' => 'Text',
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'sheet' => "this is some css",
                'href' => null,
                'link_type' => null,
            ];
        $this->postJson(DiCMS::dicmsRoute('api.sheets.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'type',
                            'name',
                            'description',
                            'sheet',
                            'href',
                            'link_type',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'css_sheets', $payload);
    }

    public function test_css_sheet_is_shown_correctly()
    {
        $sheet = CssSheet::first();
        $this->getJson(DiCMS::dicmsRoute('api.sheets.show', ['sheet' => $sheet]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' =>
                        [
                            'id' => $sheet->id,
                            'type' => $sheet->type,
                            'name' => $sheet->name,
                            'description' => $sheet->description,
                            'sheet' => $sheet->sheet,
                            'href' => $sheet->href,
                            'link_type' => $sheet->link_type,
                            'created_at' => $sheet->created_at->format('Y-m-d H:i:s'),
                            'updated_at' => $sheet->updated_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_link_css_sheet_is_updated_correctly()
    {
        $sheet = CssSheet::links()->first();
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'type' => 'Link',
                'href' => $this->faker->url,
                'link_type' => 'rel="stylesheet" something else',
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sheets.update', ['sheet' => $sheet]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $sheet->id,
                            'type' => $sheet->type->value,
                            'name' => $payload['name'],
                            'description' => $payload['description'],
                            'sheet' => $sheet->sheet,
                            'href' => $payload['href'],
                            'link_type' => $payload['link_type'],
                            'created_at' => $sheet->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_text_css_sheet_is_updated_correctly()
    {
        $sheet = CssSheet::text()->first();
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'sheet' => $this->faker->text(3000),
                'type' => 'Text',
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sheets.update', ['sheet' => $sheet]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $sheet->id,
                            'type' => $sheet->type->value,
                            'name' => $payload['name'],
                            'description' => $payload['description'],
                            'sheet' => $payload['sheet'],
                            'href' => $sheet->href,
                            'link_type' => $sheet->link_type,
                            'created_at' => $sheet->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_css_sheet_is_deleted_correctly()
    {
        $sheet = CssSheet::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.sheets.destroy', ['sheet' => $sheet]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'css_sheets', $sheet->toArray());
    }

    public function test_show_for_missing_css_sheet()
    {
        $this->getJson(DiCMS::dicmsRoute('api.sheets.show', ['sheet' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_css_sheet()
    {
        $this->putJson(DiCMS::dicmsRoute('api.sheets.update', ['sheet' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_css_sheet()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.sheets.destroy', ['sheet' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.sheets.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['type', 'name']);
    }

}
