<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;

class SongsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Song::create([
            'title' => 'Bella Bella Beat',
            'artist' => 'Nana Kwabena',
            'src' => 'audio/Bella Bella Beat.mp3',
            'cover' => 'images/dj2.jpg',
        ]);

        Song::create([
            'title' => 'Breatha',
            'artist' => 'Josh Pan',
            'src' => 'audio/Breatha.mp3',
            'cover' => 'images/dj3.jpg',
        ]);

        Song::create([
            'title' => 'Forever',
            'artist' => 'Anno Domini Beats',
            'src' => 'audio/Forever.mp3',
            'cover' => 'images/dj4.jpg',
        ]);

        Song::create([
            'title' => 'Yah Yah',
            'artist' => 'Josh Pan',
            'src' => 'audio/Yah Yah.mp3',
            'cover' => 'images/dj.jpg',
        ]);
    }
}
