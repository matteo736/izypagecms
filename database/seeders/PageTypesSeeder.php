<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('page_types')->insert([
            ['name' => 'Page', 'slug' => 'page', 'has_archive' => false ],
            ['name' => 'Blog', 'slug' => 'blog', 'has_archive' => true ],                
            ['name' => 'Archive', 'slug' => 'archive', 'has_archive' => false ],
        ]);
    }
}
