<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\Header;
use halestar\LaravelDropInCms\Resources\HeaderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class HeaderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', Header::class))
            return response()->json([], 403);
        return HeaderResource::collection(Header::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', Header::class))
            return response()->json([], 403);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'headers',
                'description' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        $header = new Header();
        $header->fill($validator->validated());
        $header->save();
        return HeaderResource::make($header)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Header $header)
    {
        if(!Gate::allows('view', $header))
            return response()->json([], 403);
        return new HeaderResource($header);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Header $header)
    {
        if(!Gate::allows('update', $header))
            return response()->json([], 403);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'headers',
                'description' => 'nullable',
                'html' => 'nullable',
                'css' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        $header->fill($validator->validated());
        $header->save();
        return new HeaderResource($header);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Header $header)
    {
        if(!Gate::allows('delete', $header))
            return response()->json([], 403);
        $header->delete();
        return response()->json([], 204);
    }
}
