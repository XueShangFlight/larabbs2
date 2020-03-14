<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name' => '分享',
                'description' => '分享创造，分享发现'
            ],
            [
                'name' => '教程',
                'description' => '学习能力，开发技巧'
            ],
            [
                'name' => '问答',
                'description' => '保持友善，互帮互助',
            ],
            [
                'name' => '公告',
                'description' => '站点公告，务必遵守',
            ],
        ];
        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->trun0cate();
    }
}
