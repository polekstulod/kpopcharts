<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Artist;
use DB;

class DashboardApiController extends Controller
{
    public function totalAlbumsSold()
    {
        $totals = Artist::withCount(['albums as total_sold' => function ($query) {
            $query->select(DB::raw("SUM(sales) as total_sold"));
        }])->get();

        return response()->json($totals);
    }

    public function combinedSales()
    {
        $combined = Artist::with(['albums' => function ($query) {
            $query->select('artist_id', DB::raw("SUM(sales) as combined_sales"))->groupBy('artist_id');
        }])->get();

        return response()->json($combined);
    }

    public function topArtist()
    {
        $topArtist = Artist::with(['albums'])
            ->get()
            ->map(function ($artist) {
                $artist->combined_sales = $artist->albums->sum('sales');
                return $artist;
            })
            ->sortByDesc('combined_sales')
            ->first();

        return response()->json($topArtist);
    }


    public function albumsByArtist(Request $request)
    {
        $artistName = $request->query('name');
        $albums = Album::whereHas('artist', function ($query) use ($artistName) {
            $query->where('name', 'like', '%' . $artistName . '%');
        })->get();

        return response()->json($albums);
    }
}
