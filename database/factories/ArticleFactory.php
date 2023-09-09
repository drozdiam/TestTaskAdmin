<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $i = 0;
        return [
            'name' => 'Article ' . $i,
            'slug' => Str::slug("Article $i",'-' ),
            'category_id' => Category::get()->random()->id,
            'image' => 'storage/images/' . $this->faker->image('public/storage/images', 400, 300, null, false),
            'text' => $this->faker->text(20),
            'order' => $i++,
            'active' => true
        ];
    }
}
