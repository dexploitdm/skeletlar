<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Post_tagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_tags')->insert([
            'post_id' => '1',
            'tag_id' => '0',
        ]);
    }
}
