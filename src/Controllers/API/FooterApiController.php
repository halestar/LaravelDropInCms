<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\Footer;
use halestar\LaravelDropInCms\Resources\FooterResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FooterApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', Footer::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return FooterResource::collection(Footer::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', Footer::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'headers',
                'description' => 'nullable',
                'html' => 'nullable',
                'css' => 'nullable',
                'data' => 'nullable|array',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $footer = new Footer();
        $footer->fill($validator->validated());
        $footer->save();
        return FooterResource::make($footer)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Footer $footer)
    {
        if(!Gate::allows('view', $footer))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return new FooterResource($footer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Footer $footer)
    {
        if(!Gate::allows('update', $footer))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'footers')->ignore($footer->id),
                    ],
                'description' => 'nullable',
                'html' => 'nullable',
                'css' => 'nullable',
                'data' => 'nullable|array',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
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
            return response()->json([], Response::HTTP_FORBIDDEN);
        $footer->delete();
        return response()->json([], 204);
    }
}
