<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Resources\FooterResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class FooterApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', Footer::class))
            return response()->json([], 403);
        return FooterResource::collection(Footer::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', Footer::class))
            return response()->json([], 403);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'footers',
                'description' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        $footer = new Footer();
        $footer->fill($validator->validated());
        $footer->save();
        return FooterResource::make($footer)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Footer $footer)
    {
        if(!Gate::allows('view', $footer))
            return response()->json([], 403);
        return new FooterResource($footer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Footer $footer)
    {
        if(!Gate::allows('update', $footer))
            return response()->json([], 403);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'footers',
                'description' => 'nullable',
                'html' => 'nullable',
                'css' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        $footer->fill($validator->validated());
        $footer->save();
        return new FooterResource($footer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Footer $footer)
    {
        if(!Gate::allows('delete', $footer))
            return response()->json([], 403);
        $footer->delete();
        return response()->json([], 204);
    }
}
