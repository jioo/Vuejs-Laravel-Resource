<?php

use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
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
            ->movies()->saveMany(factory(App\Movie::class, 10)->make());

        factory(App\Category::class)
            ->create(['name' => 'Comedy'])
            ->movies()->saveMany(factory(App\Movie::class, 10)->make());

        factory(App\Category::class)
            ->create(['name' => 'Fantasy'])
            ->movies()->saveMany(factory(App\Movie::class, 10)->make());
    }
}
