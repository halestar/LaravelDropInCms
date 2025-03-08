<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\CssSheet;
use halestar\LaravelDropInCms\Resources\CssSheetResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CssSheetApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Gate::allows('viewAny', CssSheet::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return CssSheetResource::collection(CssSheet::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Gate::allows('create', CssSheet::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:' . config('dicms.table_prefix') . 'css_sheets',
                'description' => 'nullable',
                'type' => 'required|in:Link,Text',
                'sheet' => 'required_if:type,Text',
                'href' => 'required_if:type,Link',
                'link_type' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $cssSheet = new CssSheet();
        $cssSheet->fill($validator->validated());
        $cssSheet->save();
        return CssSheetResource::make($cssSheet)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(CssSheet $sheet)
    {
        if(!Gate::allows('view', $sheet))
            return response()->json([], Response::HTTP_FORBIDDEN);
        return new CssSheetResource($sheet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CssSheet $sheet)
    {
        if(!Gate::allows('update', $sheet))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(),
            [
                'name' =>
                    [
                        'required',
                        'max:255',
                        Rule::unique(config('dicms.table_prefix') . 'css_sheets')->ignore($sheet->id),
                    ],
                'description' => 'nullable',
                'type' => 'required|in:Link,Text',
                'sheet' => 'required_if:type,Text',
                'href' => 'required_if:type,Link',
                'link_type' => 'nullable',
            ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(), Response::HTTP_BAD_REQUEST);
        $sheet->fill($validator->validated());
        $sheet->save();
        return new CssSheetResource($sheet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CssSheet $sheet)
    {
        if(!Gate::allows('delete', $sheet))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $sheet->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
