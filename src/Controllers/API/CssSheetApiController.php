<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Resources\CssSheetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CssSheetApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', CssSheet::class))
            return response()->json([], 403);
        return CssSheetResource::collection(CssSheet::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', CssSheet::class))
            return response()->json([], 403);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'css_sheets',
                'description' => 'nullable',
                'type' => 'required|in:Link,Text',
                'sheet' => 'nullable',
                'href' => 'nullable',
                'link_type' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        $cssSheet = new CssSheet();
        $cssSheet->fill($validator->validated());
        $cssSheet->save();
        return CssSheetResource::make($cssSheet)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CssSheet $cssSheet)
    {
        if(!Gate::allows('view', $cssSheet))
            return response()->json([], 403);
        return new CssSheetResource($cssSheet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CssSheet $cssSheet)
    {
        if(!Gate::allows('update', $cssSheet))
            return response()->json([], 403);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'css_sheets',
                'description' => 'nullable',
                'type' => 'required|in:Link,Text',
                'sheet' => 'nullable',
                'href' => 'nullable',
                'link_type' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        $cssSheet->fill($validator->validated());
        $cssSheet->save();
        return new CssSheetResource($cssSheet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CssSheet $cssSheet)
    {
        if(!Gate::allows('delete', $cssSheet))
            return response()->json([], 403);
        $cssSheet->delete();
        return response()->json([], 204);
    }
}
