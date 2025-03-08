<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssFooter;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\JsFooter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class FooterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.footers.index'))
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

    public function test_footer_created_successfully()
    {
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'html' => "<div>some html</div>",
                'css' => "some css",
                'data' => [],
            ];
        $this->postJson(DiCMS::dicmsRoute('api.footers.store'), $payload)
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
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'footers', $payload);
    }

    public function test_footer_is_shown_correctly()
    {
        $footer = Footer::first();
        $this->getJson(DiCMS::dicmsRoute('api.footers.show', ['footer' => $footer]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $footer->id,
                    'name' => $footer->name,
                    'description' => $footer->description,
                    'html' => $footer->html,
                    'css' => $footer->css,
                    'created_at' => $footer->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $footer->updated_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_footer_is_updated_correctly()
    {
        $footer = Footer::first();
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'html' => "<div>some html</div>",
                'css' => "some css",
            ];
        $this->putJson(DiCMS::dicmsRoute('api.footers.update', ['footer' => $footer]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $footer->id,
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                    'html' => $payload['html'],
                    'css' => $payload['css'],
                    'created_at' => $footer->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_footer_is_deleted_correctly()
    {
        $footer = Footer::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.footers.destroy', ['footer' => $footer]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'footers', $footer->toArray());
    }

    public function test_show_for_missing_footer()
    {
        $this->getJson(DiCMS::dicmsRoute('api.footers.show', ['footer' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_footer()
    {
        $this->putJson(DiCMS::dicmsRoute('api.footers.update', ['footer' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_footer()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.footers.destroy', ['footer' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.footers.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['name']);
    }

}
