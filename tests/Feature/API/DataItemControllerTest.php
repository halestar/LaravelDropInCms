<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssFooter;
use halestar\LaravelDropInCms\Models\DataItem;
use halestar\LaravelDropInCms\Models\JsFooter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataItemControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.data-items.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        '*' =>
                        [
                            'id',
                            'parent_id',
                            'name',
                            'path',
                            'url',
                            'mime',
                            'thumb',
                            'is_folder',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    public function test_data_item_created_successfully()
    {
        Storage::fake(config('dicms.media_upload_disk'));
        $file = UploadedFile::fake()->image('img.png', 800, 400)->mimeType("image/png");
        $payload =
            [
                'name' => $this->faker->word,
                'parent_id' => null,
                'is_folder' => false,
                'file' => $file,
            ];
        $response = $this->postJson(DiCMS::dicmsRoute('api.data-items.store'), $payload);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'parent_id',
                            'path',
                            'url',
                            'mime',
                            'thumb',
                            'is_folder',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        unset($payload['file']);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'data_items', $payload);
        Storage::disk(config('dicms.media_upload_disk'))->assertExists($response['data']['path']);
    }

    public function test_data_item_created_in_subfolder_successfully()
    {
        Storage::fake(config('dicms.media_upload_disk'));
        $file = UploadedFile::fake()->image('img.png', 800, 400)->mimeType("image/png");
        $folder = DataItem::where('is_folder', true)->first();
        $payload =
            [
                'name' => $this->faker->word,
                'parent_id' => $folder->id,
                'is_folder' => false,
                'file' => $file,
            ];
        $response = $this->postJson(DiCMS::dicmsRoute('api.data-items.store'), $payload);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'parent_id',
                            'path',
                            'url',
                            'mime',
                            'thumb',
                            'is_folder',
                            'created_at',
                            'updated_at',
                        ]
                ])
            ->assertJson(['data' => ['parent_id' => $folder->id]]);
        unset($payload['file']);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'data_items', $payload);
        Storage::disk(config('dicms.media_upload_disk'))->assertExists($response['data']['path']);
    }

    public function test_data_item_folder_created_successfully()
    {
        $payload =
            [
                'name' => $this->faker->word,
                'parent_id' => null,
                'is_folder' => true,
            ];
        $response = $this->postJson(DiCMS::dicmsRoute('api.data-items.store'), $payload);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'parent_id',
                            'path',
                            'url',
                            'mime',
                            'thumb',
                            'is_folder',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'data_items', $payload);
    }

    public function test_data_item_folder_created_in_subfolder_successfully()
    {
        $folder = DataItem::where('is_folder', true)->first();
        $payload =
            [
                'name' => $this->faker->word,
                'parent_id' => $folder->id,
                'is_folder' => true,
            ];
        $response = $this->postJson(DiCMS::dicmsRoute('api.data-items.store'), $payload);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'parent_id',
                            'path',
                            'url',
                            'mime',
                            'thumb',
                            'is_folder',
                            'created_at',
                            'updated_at',
                        ]
                ])
            ->assertJson(['data' => ['parent_id' => $folder->id]]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'data_items', $payload);
    }

    public function test_data_item_is_shown_correctly()
    {
        $dataItem = DataItem::where('is_folder', false)->first();
        $this->getJson(DiCMS::dicmsRoute('api.data-items.show', ['data_item' => $dataItem]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                    [
                        'id' => $dataItem->id,
                        'parent_id' => $dataItem->parent_id,
                        'name' => $dataItem->name,
                        'path' => $dataItem->path,
                        'url' => $dataItem->url,
                        'mime' => $dataItem->mime,
                        'thumb' => $dataItem->thumb,
                        'is_folder' => false,
                        'created_at' => $dataItem->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $dataItem->updated_at->format('Y-m-d H:i:s'),
                    ]
            ]);
    }

    public function test_data_item_folder_is_shown_correctly()
    {
        $dataItem = DataItem::where('is_folder', true)->first();
        $this->getJson(DiCMS::dicmsRoute('api.data-items.show', ['data_item' => $dataItem]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $dataItem->id,
                            'parent_id' => $dataItem->parent_id,
                            'name' => $dataItem->name,
                            'path' => $dataItem->path,
                            'url' => $dataItem->url,
                            'mime' => $dataItem->mime,
                            'thumb' => $dataItem->thumb,
                            'is_folder' => true,
                            'created_at' => $dataItem->created_at->format('Y-m-d H:i:s'),
                            'updated_at' => $dataItem->updated_at->format('Y-m-d H:i:s'),
                        ]
            ]);
    }

    public function test_data_item_is_updated_correctly()
    {
        $dataItem = DataItem::where('is_folder', false)->first();
        Storage::fake(config('dicms.media_upload_disk'));
        $file = UploadedFile::fake()->image('img.png', 800, 400)->mimeType("image/png");
        $payload =
            [
                'name' => $this->faker->word,
                'parent_id' => $dataItem->parent_id,
                'is_folder' => $dataItem->is_folder,
                'file' => $file,
            ];
        $response = $this->putJson(DiCMS::dicmsRoute('api.data-items.update', ['data_item' => $dataItem]), $payload);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $dataItem->id,
                            'parent_id' => $dataItem->parent_id,
                            'name' => $payload['name'],
                            'path' => $dataItem->path,
                            'url' => $dataItem->url,
                            'mime' => $dataItem->mime,
                            'thumb' => $dataItem->thumb,
                            'is_folder' => $dataItem->is_folder,
                            'created_at' => $dataItem->created_at->format('Y-m-d H:i:s'),
                        ]
                ]);

        Storage::disk(config('dicms.media_upload_disk'))->assertExists($response['data']['path']);
    }

    public function test_data_item_folder_is_updated_correctly()
    {
        $dataItem = DataItem::where('is_folder', true)->first();
        $payload =
            [
                'name' => $this->faker->word,
                'is_folder' => $dataItem->is_folder,
                'parent_id' => $dataItem->parent_id,
            ];
        $response = $this->putJson(DiCMS::dicmsRoute('api.data-items.update', ['data_item' => $dataItem]), $payload);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $dataItem->id,
                            'parent_id' => $dataItem->parent_id,
                            'name' => $payload['name'],
                            'path' => $dataItem->path,
                            'url' => $dataItem->url,
                            'mime' => $dataItem->mime,
                            'thumb' => $dataItem->thumb,
                            'is_folder' => $dataItem->is_folder,
                            'created_at' => $dataItem->created_at->format('Y-m-d H:i:s'),
                        ]
                ]);
    }

    public function test_data_item_is_deleted_correctly()
    {
        $dataItem = DataItem::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.data-items.destroy', ['data_item' => $dataItem]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'data_items', $dataItem->toArray());
    }

    public function test_show_for_missing_data_item()
    {
        $this->getJson(DiCMS::dicmsRoute('api.data-items.show', ['data_item' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_data_item()
    {
        $this->putJson(DiCMS::dicmsRoute('api.data-items.update', ['data_item' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_data_item()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.data-items.destroy', ['data_item' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.data-items.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['name']);
    }

    public function test_move_item_to_folder()
    {
        $folder = DataItem::where('is_folder', true)->first();
        $dataItem = DataItem::where('is_folder', false)
            ->whereNull('parent_id')
            ->first();
        $payload =
            [
                'name' => $dataItem->name,
                'is_folder' => $dataItem->is_folder,
                'parent_id' => $folder->id,
            ];
        $response = $this->putJson(DiCMS::dicmsRoute('api.data-items.update', ['data_item' => $dataItem]), $payload);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $dataItem->id,
                            'parent_id' => $folder->id,
                            'name' => $dataItem->name,
                            'path' => $dataItem->path,
                            'url' => $dataItem->url,
                            'mime' => $dataItem->mime,
                            'thumb' => $dataItem->thumb,
                            'is_folder' => $dataItem->is_folder,
                            'created_at' => $dataItem->created_at->format('Y-m-d H:i:s'),
                        ]
                ]);
    }

    public function test_move_item_to_root()
    {
        $dataItem = DataItem::where('is_folder', false)
            ->whereNull('parent_id')
            ->first();
        $payload =
            [
                'name' => $dataItem->name,
                'is_folder' => $dataItem->is_folder,
                'parent_id' => null,
            ];
        $response = $this->putJson(DiCMS::dicmsRoute('api.data-items.update', ['data_item' => $dataItem]), $payload);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $dataItem->id,
                            'parent_id' => null,
                            'name' => $dataItem->name,
                            'path' => $dataItem->path,
                            'url' => $dataItem->url,
                            'mime' => $dataItem->mime,
                            'thumb' => $dataItem->thumb,
                            'is_folder' => $dataItem->is_folder,
                            'created_at' => $dataItem->created_at->format('Y-m-d H:i:s'),
                        ]
                ]);
    }

}
