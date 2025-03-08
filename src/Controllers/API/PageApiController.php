<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Page;
use halestar\LaravelDropInCms\Resources\PageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', Page::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return PageResource::collection(Page::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', Page::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'pages',
                'title' => 'nullable',
                'slug' => 'required|alpha_dash|max:255',
                'path' => 'nullable|regex:/([a-zA-Z0-9\-_]*\/?)*/|max:255',
                'html' => 'nullable',
                'css' => 'nullable',
                'data' => 'nullable',
                'override_css' => 'required|boolean',
                'override_js' => 'required|boolean',
                'override_header' => 'required|boolean',
                'override_footer' => 'required|boolean',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
                'published' => 'required|boolean',
                'metadata' => 'nullable|array',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $page = new Page();
        $page->fill($validator->validated());
        $page->url = (isset($page->path)? $page->path . "/": '' ) . $page->slug;
        $page->save();
        $page->refresh();
        return PageResource::make($page)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        if(!Gate::allows('view', $page))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return new PageResource($page);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        if(!Gate::allows('update', $page))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' =>
                    [
                        'nullable',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'pages')->ignore($page->id),
                    ],
                'title' => 'nullable',
                'slug' => 'required|alpha_dash|max:255',
                'path' => 'nullable|regex:/([a-zA-Z0-9\-_]*\/?)*/|max:255',
                'html' => 'nullable',
                'css' => 'nullable',
                'data' => 'nullable',
                'override_css' => 'required|boolean',
                'override_js' => 'required|boolean',
                'override_header' => 'required|boolean',
                'override_footer' => 'required|boolean',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
                'published' => 'required|boolean',
                'metadata' => 'nullable|array',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $page->fill($validator->validated());
        $page->url = (isset($page->path)? $page->path . "/": '' ) . $page->slug;
        $page->save();
        return new PageResource($page);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if(!Gate::allows('delete', $page))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $page->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function linkCss(Request $request, Page $page)
    {
        if(!Gate::allows('update', $page))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'css' => 'present|array'
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        //validate the values
        $update = [];
        $pos = 1;
        foreach($validator->getValue('css') as $cssId)
        {
            if(!CssSheet::where('id', $cssId)->exists())
                return response()->json(['css' => 'Invalid css model'], Response::HTTP_BAD_REQUEST);
            $update[$cssId] = ['order_by' => $pos];
            $pos++;
        }
        //in this case, sync the associations.
        $page->pageCss()->sync($update);
        $page->save();
        $page->load('pageCss');
        return new PageResource($page);
    }

    public function linkJs(Request $request, Page $page)
    {
        if(!Gate::allows('update', $page))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'js' => 'present|array'
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        //validate the values
        $update = [];
        $pos = 1;
        foreach($validator->getValue('js') as $jsId)
        {
            if(!JsScript::where('id', $jsId)->exists())
                return response()->json(['js' => 'Invalid js model'], Response::HTTP_BAD_REQUEST);
            $update[$jsId] = ['order_by' => $pos];
            $pos++;
        }
        //in this case, sync the associations.
        $page->pageJs()->sync($update);
        $page->save();
        $page->load('pageJs');
        return new PageResource($page);
    }
}
