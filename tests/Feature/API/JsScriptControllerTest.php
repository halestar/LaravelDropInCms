<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssScript;
use halestar\LaravelDropInCms\Models\JsScript;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class JsScriptControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.scripts.index'))
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
                            'script',
                            'href',
                            'link_type',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    public function test_js_script_link_is_created_successfully()
    {
        $payload =
            [
                'type' => 'Link',
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'script' => null,
                'href' => $this->faker->url,
                'link_type' => 'language="text/javascript"',
            ];
        $this->postJson(DiCMS::dicmsRoute('api.scripts.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'type',
                            'name',
                            'description',
                            'script',
                            'href',
                            'link_type',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'js_scripts', $payload);
    }

    public function test_js_script_text_is_created_successfully()
    {
        $payload =
            [
                'type' => 'Text',
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'script' => "this is some js",
                'href' => null,
                'link_type' => null,
            ];
        $this->postJson(DiCMS::dicmsRoute('api.scripts.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'type',
                            'name',
                            'description',
                            'script',
                            'href',
                            'link_type',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'js_scripts', $payload);
    }

    public function test_js_script_is_shown_correctly()
    {
        $script = JsScript::first();
        $this->getJson(DiCMS::dicmsRoute('api.scripts.show', ['script' => $script]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' =>
                        [
                            'id' => $script->id,
                            'type' => $script->type,
                            'name' => $script->name,
                            'description' => $script->description,
                            'script' => $script->script,
                            'href' => $script->href,
                            'link_type' => $script->link_type,
                            'created_at' => $script->created_at->format('Y-m-d H:i:s'),
                            'updated_at' => $script->updated_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_link_js_script_is_updated_correctly()
    {
        $script = JsScript::links()->first();
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'type' => 'Link',
                'href' => $this->faker->url,
                'link_type' => 'something else',
            ];
        $this->putJson(DiCMS::dicmsRoute('api.scripts.update', ['script' => $script]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $script->id,
                            'type' => $script->type->value,
                            'name' => $payload['name'],
                            'description' => $payload['description'],
                            'script' => $script->script,
                            'href' => $payload['href'],
                            'link_type' => $payload['link_type'],
                            'created_at' => $script->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_text_js_script_is_updated_correctly()
    {
        $script = JsScript::text()->first();
        $payload =
            [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'type' => 'Text',
                'script' => $this->faker->text(3000),
            ];
        $this->putJson(DiCMS::dicmsRoute('api.scripts.update', ['script' => $script]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $script->id,
                            'type' => $script->type->value,
                            'name' => $payload['name'],
                            'description' => $payload['description'],
                            'script' => $payload['script'],
                            'href' => $script->href,
                            'link_type' => $script->link_type,
                            'created_at' => $script->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_js_script_is_deleted_correctly()
    {
        $script = JsScript::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.scripts.destroy', ['script' => $script]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'js_scripts', $script->toArray());
    }

    public function test_show_for_missing_js_script()
    {
        $this->getJson(DiCMS::dicmsRoute('api.scripts.show', ['script' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_js_script()
    {
        $this->putJson(DiCMS::dicmsRoute('api.scripts.update', ['script' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_js_script()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.scripts.destroy', ['script' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.scripts.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['type', 'name']);
    }

}
