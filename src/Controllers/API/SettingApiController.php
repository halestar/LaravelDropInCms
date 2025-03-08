<?php

namespace halestar\LaravelDropInCms\Controllers\API;

use App\Http\Controllers\Controller;
use halestar\LaravelDropInCms\Models\Site;
use halestar\LaravelDropInCms\Resources\SettingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class SettingApiController extends Controller
{
    public function get(Request $request)
    {
        if(!Gate::allows('create', Site::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $key = $request->get('key');
        if(!$key)
            return response()->json(['key' => 'Key cannot be null'], Response::HTTP_BAD_REQUEST);
        if(!is_string($key))
            return response()->json(['key' => 'Key must be a string'], Response::HTTP_BAD_REQUEST);
        return response()->json(['data' => ['value' => config('dicms.settings_class')::get($key)]], Response::HTTP_OK);
    }

    public function set(Request $request)
    {
        if(!Gate::allows('create', Site::class))
            return response()->json([], Response::HTTP_FORBIDDEN);
        $key = $request->get('key');
        if(!$key)
            return response()->json(['key' => 'Key cannot be null'], Response::HTTP_BAD_REQUEST);
        if(!is_string($key))
            return response()->json(['key' => 'Key must be a string'], Response::HTTP_BAD_REQUEST);
        $value = $request->get('value');
        config('dicms.settings_class')::set($key, $value);
        return response()->json(['data' => ['key' => $key, 'value' => $value]], 200);
    }
}
