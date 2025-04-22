<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post_Type;

class PostTypeSeeder extends Seeder
{
    public function run(): void
    {
        $postType = [
            'Post' => [
                'name' => 'Post',
                'slug' => 'post',
                'has_archive' => true,
                'public' => true,
                'labels' => json_encode([
                    'singular' => 'Post',
                    'plural' => 'Posts'
                ]),
                'supports_title' => true,
                'supports_content' => true
            ],
            'Page' => [
                'name' => 'Page',
                'slug' => 'page',
                'has_archive' => true,
                'public' => true,
                'labels' => json_encode([
                    'singular' => 'Page',
                    'plural' => 'Pages'
                ]),
                'supports_title' => true,
                'supports_content' => true
            ],
        ];
        foreach ($postType as $type) {
            Post_Type::create($type);
        }
    }
}
