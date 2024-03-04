<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Artist;

class ArtistApiController extends Controller
{
    public function index()
    {
        $artists = Artist::all();
        return response()->json($artists);
    }

    public function show(Artist $artist)
    {
        return response()->json($artist);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'email' => 'required|email',
            'debut' => 'required|date',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $imagePath = $request->file('image')->store('images', 'public');

        $artist = Artist::create([
            'name' => $request->name,
            'code' => $request->code,
            'email' => $request->email,
            'debut' => $request->debut,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Artist created successfully', 'artist' => $artist], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        // Find the artist or fail with a 404 error
        $artist = Artist::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'email' => 'required|email',
            'debut' => 'required|date',
            'description' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update the image if it exists in the request
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $artist->image = $imagePath;
        }

        // Update the artist's information
        $artist->name = $request->name;
        $artist->code = $request->code;
        $artist->email = $request->email;
        $artist->debut = $request->debut;
        $artist->description = $request->description;

        $artist->save();

        return response()->json(['message' => 'Artist updated successfully', 'artist' => $artist], Response::HTTP_OK);
    }

    public function destroy(Artist $artist)
    {
        if ($artist->albums()->count() > 0) {
            return response()->json(['message' => 'Cannot delete artist with existing albums'], Response::HTTP_FORBIDDEN);
        }

        $artist->delete();
        return response()->json(['message' => 'Artist deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
