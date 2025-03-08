<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SiteControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.sites.index'))
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
                            'title',
                            'body_attr',
                            'active',
                            'archived',
                            'defaultHeader',
                            'defaultFooter',
                            'homepage_url',
                            'favicon',
                            'tag',
                            'options',
                            'siteCss',
                            'siteJs',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    public function test_site_created_successfully()
    {
        $payload =
            [
                'name' => $this->faker->word,
                'active' => false,
                'archived' => false,
            ];
        $this->postJson(DiCMS::dicmsRoute('api.sites.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            'id',
                            'name',
                            'description',
                            'title',
                            'body_attr',
                            'active',
                            'archived',
                            'defaultHeader',
                            'defaultFooter',
                            'homepage_url',
                            'favicon',
                            'tag',
                            'options',
                            'siteCss',
                            'siteJs',
                            'created_at',
                            'updated_at',
                        ]
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'sites', $payload);
    }

    public function test_site_is_shown_correctly()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $this->getJson(DiCMS::dicmsRoute('api.sites.show', ['site' => $site]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $site->name,
                            'description' => $site->description,
                            'title' => $site->title,
                            'body_attr' => $site->body_attr,
                            'active' => $site->active,
                            'archived' => $site->archived,
                            'defaultHeader' => $site->defaultHeader? $site->defaultHeader->toArray(): null,
                            'defaultFooter' => $site->defaultFooter? $site->defaultFooter->toArray(): null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css,
                            'siteJs' => $js,
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                            'updated_at' => $site->updated_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_site_is_updated_correctly()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $payload =
            [
                'name' => $this->faker->word,
                'title' => $this->faker->sentence,
                'description' => $this->faker->sentence,
                'active' => false,
                'archived' => true,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sites.update', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $payload['name'],
                            'description' => $payload['description'],
                            'title' => $payload['title'],
                            'body_attr' => $site->body_attr,
                            'active' => $payload['active'],
                            'archived' => $payload['archived'],
                            'defaultHeader' => $site->defaultHeader? $site->defaultHeader->toArray(): null,
                            'defaultFooter' => $site->defaultFooter? $site->defaultFooter->toArray(): null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css,
                            'siteJs' => $js,
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_site_is_deleted_correctly()
    {
        $site = Site::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.sites.destroy', ['site' => $site]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'sites', ['id' => $site->id]);
    }

    public function test_show_for_missing_site()
    {
        $this->getJson(DiCMS::dicmsRoute('api.sites.show', ['site' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_site()
    {
        $this->putJson(DiCMS::dicmsRoute('api.sites.update', ['site' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_site()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.sites.destroy', ['site' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.sites.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['name']);
    }

    public function test_set_default_header()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        if($site->header_id)
            $header = Header::whereNot('id', $site->id)->inRandomOrder()->first();
        else
            $header = Header::inRandomOrder()->first();
        $payload =
            [
                'name' => $site->name,
                'active' => $site->active,
                'archived' => $site->archived,
                'header_id' => $header->id,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sites.update', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $site->name,
                            'description' => $site->description,
                            'title' => $site->title,
                            'body_attr' => $site->body_attr,
                            'active' => $site->active,
                            'archived' => $site->archived,
                            'defaultHeader' => $header->toArray(),
                            'defaultFooter' => $site->defaultFooter? $site->defaultFooter->toArray(): null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css,
                            'siteJs' => $js,
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_clear_default_header()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $payload =
            [
                'name' => $site->name,
                'active' => $site->active,
                'archived' => $site->archived,
                'header_id' => null,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sites.update', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $site->name,
                            'description' => $site->description,
                            'title' => $site->title,
                            'body_attr' => $site->body_attr,
                            'active' => $site->active,
                            'archived' => $site->archived,
                            'defaultHeader' => null,
                            'defaultFooter' => $site->defaultFooter? $site->defaultFooter->toArray(): null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css,
                            'siteJs' => $js,
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_set_default_footer()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        if($site->footer_id)
            $footer = Footer::whereNot('id', $site->id)->inRandomOrder()->first();
        else
            $footer = Footer::inRandomOrder()->first();
        $payload =
            [
                'name' => $site->name,
                'active' => $site->active,
                'archived' => $site->archived,
                'footer_id' => $footer->id,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sites.update', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                    [
                        'id' => $site->id,
                        'name' => $site->name,
                        'description' => $site->description,
                        'title' => $site->title,
                        'body_attr' => $site->body_attr,
                        'active' => $site->active,
                        'archived' => $site->archived,
                        'defaultHeader' => $site->defaultHeader? $site->defaultHeader->toArray(): null,
                        'defaultFooter' => $footer->toArray(),
                        'homepage_url' => $site->homepage_url,
                        'favicon' => $site->favicon,
                        'tag' => $site->tag,
                        'options' => $site->options,
                        'siteCss' => $css,
                        'siteJs' => $js,
                        'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                    ]
                ]
            );
    }

    public function test_clear_default_footer()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $payload =
            [
                'name' => $site->name,
                'active' => $site->active,
                'archived' => $site->archived,
                'footer_id' => null,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.sites.update', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $site->name,
                            'description' => $site->description,
                            'title' => $site->title,
                            'body_attr' => $site->body_attr,
                            'active' => $site->active,
                            'archived' => $site->archived,
                            'defaultHeader' => $site->defaultHeader? $site->defaultHeader->toArray(): null,
                            'defaultFooter' => null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css,
                            'siteJs' => $js,
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_set_css()
    {
        $site = Site::first();
        $js = [];
        foreach ($site->siteJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $css = CssSheet::inRandomOrder()->limit(2)->get();
        $payload = ['css' => $css->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.css', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $site->name,
                            'description' => $site->description,
                            'title' => $site->title,
                            'body_attr' => $site->body_attr,
                            'active' => $site->active,
                            'archived' => $site->archived,
                            'defaultHeader' => $site->defaultHeader? $site->defaultHeader->toArray(): null,
                            'defaultFooter' => $site->defaultFooter? $site->defaultFooter->toArray(): null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css->toArray(),
                            'siteJs' => $js,
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_set_js()
    {
        $site = Site::first();
        $css = [];
        foreach ($site->siteCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = JsScript::inRandomOrder()->limit(2)->get();
        $payload = ['js' => $js->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.js', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [
                            'id' => $site->id,
                            'name' => $site->name,
                            'description' => $site->description,
                            'title' => $site->title,
                            'body_attr' => $site->body_attr,
                            'active' => $site->active,
                            'archived' => $site->archived,
                            'defaultHeader' => $site->defaultHeader? $site->defaultHeader->toArray(): null,
                            'defaultFooter' => $site->defaultFooter? $site->defaultFooter->toArray(): null,
                            'homepage_url' => $site->homepage_url,
                            'favicon' => $site->favicon,
                            'tag' => $site->tag,
                            'options' => $site->options,
                            'siteCss' => $css,
                            'siteJs' => $js->toArray(),
                            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                        ]
                ]
            );
    }

    public function test_rearrange_css()
    {
        $site = Site::first();
        $shuffledCss = $site->siteCss->shuffle();
        $shufflePayload = ['css' => $shuffledCss->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.css', ['site' => $site]), $shufflePayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' => ['siteCss' => $shuffledCss->toArray()],
                ]
            );
    }

    public function test_rearrange_js()
    {
        $site = Site::first();
        $shuffledJs = $site->siteJs->shuffle();
        $shufflePayload = ['js' => $shuffledJs->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.js', ['site' => $site]), $shufflePayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' => ['siteJs' => $shuffledJs->toArray()],
                ]
            );
    }

    public function test_empty_css()
    {
        $site = Site::first();
        $emptyPayload = ['css' => []];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.css', ['site' => $site]), $emptyPayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' => ['siteCss' => []],
                ]
            );
    }

    public function test_empty_js()
    {
        $site = Site::first();
        $emptyPayload = ['js' => []];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.js', ['site' => $site]), $emptyPayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' => ['siteJs' => []],
                ]
            );
    }

    public function test_invalid_css_linking()
    {
        $site = Site::first();
        $payload = ['css' => [1535]];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.css', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['css']);
    }

    public function test_invalid_js_linking()
    {
        $site = Site::first();
        $payload = ['js' => [1535]];
        $this->postJson(DiCMS::dicmsRoute('api.sites.link.js', ['site' => $site]), $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['js']);
    }

}
