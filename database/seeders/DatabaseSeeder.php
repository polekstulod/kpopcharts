<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\Album;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        $file = public_path('Data Reference (ALBUM SALES).csv');

        function import_CSV($filename, $delimiter = ',')
        {
            if (!file_exists($filename) || !is_readable($filename))
                return false;
            $header = null;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== false) {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                    if (!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }
            return $data;
        }

        $records = import_CSV($file);

        foreach ($records as $record) {
            // Check if the artist exists or not
            $artist = Artist::where('name', $record['﻿Artist'])->first();

            if (!$artist) {
                // Create a new artist if it doesn't exist
                $artist = Artist::create([
                    'name' => $record['﻿Artist'],
                    'code' => Artist::factory()->raw()['code'],
                    'email' => Artist::factory()->raw()['email'],
                    'debut' => Artist::factory()->raw()['debut'],
                    'description' => Artist::factory()->raw()['description'],
                    'image' => Artist::factory()->raw()['image'],
                ]);
            }

            // Create a new album for the artist
            Album::create([
                'name' => $record['Album'],
                'artist_id' => $artist->id,
                'date_released' => $record['Date Released'],
                'sales' => $record['2022 Sales'],
                'genre' => Album::factory()->raw()['genre'],
                'cover' => Album::factory()->raw()['cover'],
                'description' => Album::factory()->raw()['description'],
            ]);
        }
    }
}
