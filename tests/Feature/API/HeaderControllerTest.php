<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssHeader;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\JsHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class HeaderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.headers.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        '*' =>
                        [
                            'id',
                            'name',
                            'description',
                            'html',
                            'css',
                            'data',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    public function test_header_created_successfully()
    {
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'html' => "<div>some html</div>",
                'css' => "some css",
                'data' => [],
            ];
        $this->postJson(DiCMS::dicmsRoute('api.headers.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'description',
                    'html',
                    'css',
                    'data',
                    'created_at',
                    'updated_at',
                ]);
        unset($payload['data']);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'headers', $payload);
    }

    public function test_header_is_shown_correctly()
    {
        $header = Header::first();
        $this->getJson(DiCMS::dicmsRoute('api.headers.show', ['header' => $header]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $header->id,
                    'name' => $header->name,
                    'description' => $header->description,
                    'html' => $header->html,
                    'css' => $header->css,
                    'created_at' => $header->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $header->updated_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_header_is_updated_correctly()
    {
        $header = Header::first();
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'html' => "<div>some html</div>",
                'css' => "some css",
            ];
        $this->putJson(DiCMS::dicmsRoute('api.headers.update', ['header' => $header]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $header->id,
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                    'html' => $payload['html'],
                    'css' => $payload['css'],
                    'created_at' => $header->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_header_is_deleted_correctly()
    {
        $header = Header::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.headers.destroy', ['header' => $header]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'headers', $header->toArray());
    }

    public function test_show_for_missing_header()
    {
        $this->getJson(DiCMS::dicmsRoute('api.headers.show', ['header' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_header()
    {
        $this->putJson(DiCMS::dicmsRoute('api.headers.update', ['header' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_header()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.headers.destroy', ['header' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.headers.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['name']);
    }

}
