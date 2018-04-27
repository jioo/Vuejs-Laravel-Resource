<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Category::class)
            ->create(['name' => 'Action'])
            ->articles()->saveMany(factory(App\Article::class, 10)->make());

        factory(App\Category::class)
            ->create(['name' => 'Comedy'])
            ->articles()->saveMany(factory(App\Article::class, 10)->make());

        factory(App\Category::class)
            ->create(['name' => 'Fantasy'])
            ->articles()->saveMany(factory(App\Article::class, 10)->make());
    }
}
