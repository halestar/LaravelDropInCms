<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_set_setting(): void
    {
        // Fake data for testing
        $payload =
            [
                'key' => 'site_theme',
                'value' => 'dark-mode',

            ];

        $this->postJson(DiCMS::dicmsRoute('api.settings.set'), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => ['key', 'value'],
            ])
            ->assertJsonFragment($payload);

        // Verify data is updated in database
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'settings', $payload);
    }

    public function test_get_setting(): void
    {
        // Fake data for testing
        $payload =
            [
                'key' => 'site_theme',
                'value' => 'dark-mode',

            ];

        $this->postJson(DiCMS::dicmsRoute('api.settings.set'), $payload)
            ->assertStatus(Response::HTTP_OK);
        $this->getJson(DiCMS::dicmsRoute('api.settings.get', ['key' => 'site_theme']))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => ['value' => 'dark-mode']]);
    }

    public function test_set_invalid_key()
    {
        $payload =
            [
                'key' => 123456,
                'value' => 'dark-mode',

            ];
        $this->postJson(DiCMS::dicmsRoute('api.settings.set'), $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['key']);
    }

    public function test_set_null_key()
    {
        $payload =
            [
                'key' => null,
                'value' => 'dark-mode',

            ];
        $this->postJson(DiCMS::dicmsRoute('api.settings.set'), $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['key']);
    }

    public function test_get_nonexistent_key()
    {
        $this->getJson(DiCMS::dicmsRoute('api.settings.get', ['key' => 'something-that-doesnt-exist']))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => ['value' => null]]);
    }

    public function test_get_null_key()
    {
        $this->getJson(DiCMS::dicmsRoute('api.settings.get', ['key' => null]))
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['key']);
    }


}
