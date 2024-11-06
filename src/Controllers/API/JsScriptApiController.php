<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Resources\JsScriptResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class JsScriptApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', JsScript::class))
            return response()->json([], 403);
        return JsScriptResource::collection(JsScript::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', JsScript::class))
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
        $jsScript = new JsScript();
        $jsScript->fill($validator->validated());
        $jsScript->save();
        return JsScriptResource::make($jsScript)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JsScript $jsScript)
    {
        if(!Gate::allows('view', $jsScript))
            return response()->json([], 403);
        return new JsScriptResource($jsScript);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JsScript $jsScript)
    {
        if(!Gate::allows('update', $jsScript))
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
        $jsScript->fill($validator->validated());
        $jsScript->save();
        return new JsScriptResource($jsScript);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JsScript $jsScript)
    {
        if(!Gate::allows('delete', $jsScript))
            return response()->json([], 403);
        $jsScript->delete();
        return response()->json([], 204);
    }
}
