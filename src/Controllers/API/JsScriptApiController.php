<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\JsScript;
use halestar\LaravelDropInCms\Resources\JsScriptResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JsScriptApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', JsScript::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return JsScriptResource::collection(JsScript::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', JsScript::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'css_sheets',
                'description' => 'nullable',
                'type' => 'required|in:Link,Text',
                'script' => 'required_if:type,Text',
                'href' => 'required_if:type,Link',
                'link_type' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $jsScript = new JsScript();
        $jsScript->fill($validator->validated());
        $jsScript->save();
        return JsScriptResource::make($jsScript)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(JsScript $script)
    {
        if(!Gate::allows('view', $script))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return new JsScriptResource($script);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JsScript $script)
    {
        if(!Gate::allows('update', $script))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' =>
                    [
                        'nullable',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'js_scripts')->ignore($script->id),
                    ],
                'description' => 'nullable',
                'type' => 'required|in:Link,Text',
                'script' => 'required_if:type,Text',
                'href' => 'required_if:type,Link',
                'link_type' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $script->fill($validator->validated());
        $script->save();
        return new JsScriptResource($script);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JsScript $script)
    {
        if(!Gate::allows('delete', $script))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $script->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
