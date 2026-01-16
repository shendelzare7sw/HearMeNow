<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Pop',
            'Rock',
            'Hip Hop',
            'Jazz',
            'Classical',
            'Electronic',
            'R&B',
            'Country',
            'Folk',
            'Metal',
            'Indie',
            'Blues',
            'Reggae',
            'Latin',
            'K-Pop',
            'Dance',
            'Soul',
            'Punk',
            'Disco',
            'Alternative',
            'Soundtrack',
            'Acoustic',
            'Instrumental',
            'Other',
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
                'slug' => Str::slug($genre),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
