<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Album;

class AlbumApiController extends Controller
{
    public function index()
    {
        $albums = Album::all();
        return response()->json($albums);
    }

    public function show(Album $album)
    {
        return response()->json($album);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'artist_id' => 'required|exists:artists,id',
            'date_released' => 'required|date',
            'genre' => 'required',
            'sales' => 'required',
            'description' => 'required',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $imagePath = $request->file('cover')->store('images', 'public');

        $album = Album::create([
            'name' => $request->name,
            'artist_id' => $request->artist_id,
            'date_released' => $request->date_released,
            'genre' => $request->genre,
            'sales' => $request->sales,
            'description' => $request->description,
            'cover' => $imagePath,
        ]);

        return response()->json(['message' => 'Album created successfully', 'album' => $album], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        // Find the album or fail with a 404 error
        $album = Album::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'artist_id' => 'required|exists:artists,id',
            'date_released' => 'required|date',
            'genre' => 'required',
            'sales' => 'required',
            'description' => 'required',
            'cover' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update the cover image if a new one is provided
        if ($request->hasFile('cover')) {
            $imagePath = $request->file('cover')->store('images', 'public');
            $album->cover = $imagePath;
        }

        // Update the album's other information
        $album->name = $request->name;
        $album->artist_id = $request->artist_id;
        $album->date_released = $request->date_released;
        $album->genre = $request->genre;
        $album->sales = $request->sales;
        $album->description = $request->description;

        $album->save();

        return response()->json(['message' => 'Album updated successfully', 'album' => $album], Response::HTTP_OK);
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return response()->json(null, 204);
    }
}
