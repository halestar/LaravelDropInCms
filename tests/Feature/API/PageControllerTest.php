<?php

namespace halestar\LaravelDropInCms\Tests\Feature\API;

use halestar\LaravelDropInCms\DiCMS;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $seed = true;


    /**
     * A basic test example.
     */
    public function test_index_returns_data_in_valid_format(): void
    {
        $this->getJson(DiCMS::dicmsRoute('api.pages.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        '*' =>
                        [
                            'id',
                            'plugin_page',
                            'plugin',
                            'name',
                            'slug',
                            'title',
                            'path',
                            'url',
                            'override_css',
                            'pageCss',
                            'override_js',
                            'pageJs',
                            'override_header',
                            'defaultHeader',
                            'override_footer',
                            'defaultFooter',
                            'html',
                            'css',
                            'data',
                            'published',
                            'metadata',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    public function test_page_created_successfully()
    {
        $payload =
            [
                'name' => $this->faker->word,
                'slug' => $this->faker->word,
                'override_css' => false,
                'override_js' => false,
                'override_header' => false,
                'override_footer' => false,
                'published' => false,
            ];
        $this->postJson(DiCMS::dicmsRoute('api.pages.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'plugin_page',
                    'plugin',
                    'name',
                    'slug',
                    'title',
                    'path',
                    'url',
                    'override_css',
                    'pageCss',
                    'override_js',
                    'pageJs',
                    'override_header',
                    'defaultHeader',
                    'override_footer',
                    'defaultFooter',
                    'html',
                    'css',
                    'data',
                    'published',
                    'metadata',
                    'created_at',
                    'updated_at',
                ]);
        $this->assertDatabaseHas(config('dicms.table_prefix') . 'pages', $payload);
    }

    public function test_page_is_shown_correctly()
    {
        $page = Page::first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $this->getJson(DiCMS::dicmsRoute('api.pages.show', ['page' => $page]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => $page->override_header,
                    'defaultHeader' => $page->defaultHeader? $page->defaultHeader->toArray(): null,
                    'override_footer' => $page->override_footer,
                    'defaultFooter' => $page->defaultFooter? $page->defaultFooter->toArray(): null,
                    'html' => $page->html,
                    'css' => $page->css,
                    'published' => $page->published,
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $page->updated_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_page_is_updated_correctly()
    {
        $page = Page::first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $payload =
            [
                'name' => $this->faker->word,
                'title' => $this->faker->sentence,
                'slug' => $page->slug,
                'path' => $page->path,
                'html' => "<div>some html</div>",
                'css' => "some css",
                'override_css' => $page->override_css,
                'override_js' => $page->override_js,
                'override_header' => $page->override_header,
                'override_footer' => $page->override_footer,
                'header_id' => $page->header_id,
                'footer_id' => $page->footer_id,
                'metadata' => $page->metadata,
                'published'=> true,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.pages.update', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $payload['name'],
                    'slug' => $page->slug,
                    'title' => $payload['title'],
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => $page->override_header,
                    'defaultHeader' => $page->defaultHeader? $page->defaultHeader->toArray(): null,
                    'override_footer' => $page->override_footer,
                    'defaultFooter' => $page->defaultFooter? $page->defaultFooter->toArray(): null,
                    'html' => $payload['html'],
                    'css' => $payload['css'],
                    'published' => $payload['published'],
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_page_is_deleted_correctly()
    {
        $page = Page::first();
        $this->deleteJson(DiCMS::dicmsRoute('api.pages.destroy', ['page' => $page]))
            ->assertNoContent();
        $this->assertDatabaseMissing(config('dicms.table_prefix') . 'pages', $page->toArray());
    }

    public function test_show_for_missing_page()
    {
        $this->getJson(DiCMS::dicmsRoute('api.pages.show', ['page' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_for_missing_page()
    {
        $this->putJson(DiCMS::dicmsRoute('api.pages.update', ['page' => 0]), [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_for_missing_page()
    {
        $this->deleteJson(DiCMS::dicmsRoute('api.pages.destroy', ['page' => 0]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_store_with_missing_data()
    {
        $this->postJson(DiCMS::dicmsRoute('api.pages.store'), [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['name']);
    }

    public function test_override_header()
    {
        $page = Page::where('override_header', false)->first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $header = Header::inRandomOrder()->first();
        $payload =
            [
                'name' =>$page->name,
                'title' => $page->title,
                'slug' => $page->slug,
                'path' => $page->path,
                'html' => $page->html,
                'css' => $page->css,
                'override_css' => $page->override_css,
                'override_js' => $page->override_js,
                'override_header' => true,
                'override_footer' => $page->override_footer,
                'header_id' => $header->id,
                'footer_id' => $page->footer_id,
                'metadata' => $page->metadata,
                'published'=> true,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.pages.update', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $payload['name'],
                    'slug' => $page->slug,
                    'title' => $payload['title'],
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => true,
                    'defaultHeader' => $header->toArray(),
                    'override_footer' => $page->override_footer,
                    'defaultFooter' => $page->defaultFooter? $page->defaultFooter->toArray(): null,
                    'html' => $payload['html'],
                    'css' => $payload['css'],
                    'published' => $payload['published'],
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_override_footer()
    {
        $page = Page::where('override_footer', false)->first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $footer = Footer::inRandomOrder()->first();
        $payload =
            [
                'name' =>$page->name,
                'title' => $page->title,
                'slug' => $page->slug,
                'path' => $page->path,
                'html' => $page->html,
                'css' => $page->css,
                'override_css' => $page->override_css,
                'override_js' => $page->override_js,
                'override_header' => $page->override_header,
                'override_footer' => true,
                'header_id' => $page->header_id,
                'footer_id' => $footer->id,
                'metadata' => $page->metadata,
                'published'=> true,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.pages.update', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $payload['name'],
                    'slug' => $page->slug,
                    'title' => $payload['title'],
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => $page->override_header,
                    'defaultHeader' => $page->defaultHeader? $page->defaultHeader->toArray(): null,
                    'override_footer' => true,
                    'defaultFooter' => $footer->toArray(),
                    'html' => $page->html,
                    'css' => $payload['css'],
                    'published' => $payload['published'],
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_clear_header()
    {
        $page = Page::where('override_header', false)->first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $payload =
            [
                'name' =>$page->name,
                'title' => $page->title,
                'slug' => $page->slug,
                'path' => $page->path,
                'html' => $page->html,
                'css' => $page->css,
                'override_css' => $page->override_css,
                'override_js' => $page->override_js,
                'override_header' => false,
                'override_footer' => $page->override_footer,
                'header_id' => null,
                'footer_id' => $page->footer_id,
                'metadata' => $page->metadata,
                'published'=> true,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.pages.update', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $payload['name'],
                    'slug' => $page->slug,
                    'title' => $payload['title'],
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => false,
                    'defaultHeader' => null,
                    'override_footer' => $page->override_footer,
                    'defaultFooter' => $page->defaultFooter? $page->defaultFooter->toArray(): null,
                    'html' => $payload['html'],
                    'css' => $payload['css'],
                    'published' => $payload['published'],
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_clear_override_footer()
    {
        $page = Page::where('override_footer', false)->first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $payload =
            [
                'name' =>$page->name,
                'title' => $page->title,
                'slug' => $page->slug,
                'path' => $page->path,
                'html' => $page->html,
                'css' => $page->css,
                'override_css' => $page->override_css,
                'override_js' => $page->override_js,
                'override_header' => $page->override_header,
                'override_footer' => false,
                'header_id' => $page->header_id,
                'footer_id' => null,
                'metadata' => $page->metadata,
                'published'=> true,
            ];
        $this->putJson(DiCMS::dicmsRoute('api.pages.update', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $payload['name'],
                    'slug' => $page->slug,
                    'title' => $payload['title'],
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => $page->override_header,
                    'defaultHeader' => $page->defaultHeader? $page->defaultHeader->toArray(): null,
                    'override_footer' => false,
                    'defaultFooter' => null,
                    'html' => $page->html,
                    'css' => $payload['css'],
                    'published' => $payload['published'],
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_override_css()
    {
        $page = Page::where('override_css', false)->first();
        $js = [];
        foreach ($page->pageJs as $jsSheet)
            $js[] = $jsSheet->toArray();
        $css = CssSheet::inRandomOrder()->limit(2)->get();
        $payload = ['css' => $css->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.css', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css->toArray(),
                    'override_js' => $page->override_js,
                    'pageJs' => $js,
                    'override_header' => $page->override_header,
                    'defaultHeader' => $page->defaultHeader? $page->defaultHeader->toArray(): null,
                    'override_footer' => $page->override_footer,
                    'defaultFooter' => $page->defaultFooter? $page->defaultFooter->toArray(): null,
                    'html' => $page->html,
                    'css' => $page->css,
                    'published' => $page->published,
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_override_js()
    {
        $page = Page::where('override_js', false)->first();
        $css = [];
        foreach ($page->pageCss as $cssSheet)
            $css[] = $cssSheet->toArray();
        $js = JsScript::inRandomOrder()->limit(2)->get();
        $payload = ['js' => $js->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.js', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'id' => $page->id,
                    'plugin_page' => $page->plugin_page,
                    'plugin' => $page->plugin,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'path' => $page->path,
                    'url' => $page->url,
                    'override_css' => $page->override_css,
                    'pageCss' => $css,
                    'override_js' => $page->override_js,
                    'pageJs' => $js->toArray(),
                    'override_header' => $page->override_header,
                    'defaultHeader' => $page->defaultHeader? $page->defaultHeader->toArray(): null,
                    'override_footer' => $page->override_footer,
                    'defaultFooter' => $page->defaultFooter? $page->defaultFooter->toArray(): null,
                    'html' => $page->html,
                    'css' => $page->css,
                    'published' => $page->published,
                    'created_at' => $page->created_at->format('Y-m-d H:i:s'),
                ]
            );
    }

    public function test_rearrange_css()
    {
        $page = Page::first();
        $css = CssSheet::inRandomOrder()->limit(3)->get();
        $payload = ['css' => $css->pluck('id')->toArray()];
        $shuffledCss = $css->shuffle();
        $shufflePayload = ['css' => $shuffledCss->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.css', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageCss' => $css->toArray(),
                ]
            );
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.css', ['page' => $page]), $shufflePayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageCss' => $shuffledCss->toArray(),
                ]
            );
    }

    public function test_rearrange_js()
    {
        $page = Page::first();
        $js = JsScript::inRandomOrder()->limit(3)->get();
        $payload = ['js' => $js->pluck('id')->toArray()];
        $shuffledJs = $js->shuffle();
        $shufflePayload = ['js' => $shuffledJs->pluck('id')->toArray()];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.js', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageJs' => $js->toArray(),
                ]
            );
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.js', ['page' => $page]), $shufflePayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageJs' => $shuffledJs->toArray(),
                ]
            );
    }

    public function test_empty_css()
    {
        $page = Page::first();
        $css = CssSheet::inRandomOrder()->limit(3)->get();
        $payload = ['css' => $css->pluck('id')->toArray()];
        $emptyPayload = ['css' => []];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.css', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageCss' => $css->toArray(),
                ]
            );
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.css', ['page' => $page]), $emptyPayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageCss' => [],
                ]
            );
    }

    public function test_empty_js()
    {
        $page = Page::first();
        $js = JsScript::inRandomOrder()->limit(3)->get();
        $payload = ['js' => $js->pluck('id')->toArray()];
        $emptyPayload = ['js' => []];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.js', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageJs' => $js->toArray(),
                ]
            );
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.js', ['page' => $page]), $emptyPayload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'pageJs' => [],
                ]
            );
    }

    public function test_invalid_css_linking()
    {
        $page = Page::first();
        $payload = ['css' => [1535]];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.css', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['css']);
    }

    public function test_invalid_js_linking()
    {
        $page = Page::first();
        $payload = ['js' => [1535]];
        $this->postJson(DiCMS::dicmsRoute('api.pages.link.js', ['page' => $page]), $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['js']);
    }

}
