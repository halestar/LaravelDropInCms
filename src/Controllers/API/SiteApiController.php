<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Enums\WrapperTagType;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Models\Site;
use halestar\LaravelDropInCms\Resources\SiteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SiteApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', Site::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return SiteResource::collection(Site::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', Site::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'sites',
                'description' => 'nullable',
                'title' => 'nullable|max:255',
                'body_attr' => 'nullable',
                'active' => 'required|boolean',
                'archived' => 'required|boolean',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
                'homepage_url' => 'nullable',
                'favicon' => 'nullable|url',
                'tag' => ['nullable', Rule::in(WrapperTagType::values())],
                'options' => 'nullable',
                'metadata' => 'nullable|array',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $site = new Site();
        $site->fill($validator->validated());
        $site->save();
        $site->refresh();
        return SiteResource::make($site)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        if(!Gate::allows('view', $site))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return new SiteResource($site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        if(!Gate::allows('update', $site))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' =>
                    [
                        'nullable',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'sites')->ignore($site->id),
                    ],
                'description' => 'nullable',
                'title' => 'nullable|max:255',
                'body_attr' => 'nullable',
                'active' => 'required|boolean',
                'archived' => 'required|boolean',
                'header_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'headers,id',
                'footer_id' => 'nullable|exists:' . config('dicms.table_prefix') . 'footers,id',
                'homepage_url' => 'nullable',
                'favicon' => 'nullable|url',
                'tag' => ['nullable', Rule::in(WrapperTagType::values())],
                'options' => 'nullable',
                'metadata' => 'nullable|array',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $site->fill($validator->validated());
        $site->save();
        return new SiteResource($site);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        if(!Gate::allows('delete', $site))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $site->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function linkCss(Request $request, Site $site)
    {
        if(!Gate::allows('update', $site))
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
        $site->siteCss()->sync($update);
        $site->save();
        $site->load('siteCss');
        return new SiteResource($site);
    }

    public function linkJs(Request $request, Site $site)
    {
        if(!Gate::allows('update', $site))
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
        $site->siteJs()->sync($update);
        $site->save();
        $site->load('siteJs');
        return new SiteResource($site);
    }
}
