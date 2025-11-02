<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['download']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $file = File::where('user_id', $user->id)->get();
        return FileResource::collection($file);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request)
    {
        $user = auth()->user();

        $disk = $request->private ? 'private' : 'public';
        $path = $request->file('file')->store($user->id, $disk);

        $file = new File();
        $file->user()->associate($user);
        $file->private = $request->private;
        $file->path = $path;
        $file->expires_at = now()->addDay(3);
        $file->save();
        $file->url = URL::temporarySignedRoute('download', $file->expires_at, ['file' => $file->id]);
        $file->save();
        return FileResource::make($file);
    }

    /**
     * Display the specified resource.
     */
    public function show($file)
    {
        $user = auth()->user();
        $data = File::where("user_id", $user->id)->where("id", $file)->first();
        if ($data === null) {
            return response()->json(["message" => "Link not found"], 404);
        }
        return FileResource::make($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $user = auth()->user();
        if ($file->user_id !== $user->id)
        {
            return response()->json(["message" => "Link not found"], 404);
        }
        $file->delete();
        return response()->json(["message" => "deleted successfully"], 200);
    }

    public function download($file)
    {
        $user = auth()->user();
        $file = File::where("id", $file)->first();
        if ($file === null) {return response()->json(["message" => "File not found"], 404);}
        $disk = $file->private ? 'private' : 'public';
        if ($disk === 'private' && $user && $user->id === $file->user_id) {
            return Storage::disk("$disk")->download($file->path);
        }
        elseif ($disk === 'public'){
            return Storage::disk($disk)->download($file->path);
        }
        return response()->json(["message" => "Forbidden"], 403);
    }
}
