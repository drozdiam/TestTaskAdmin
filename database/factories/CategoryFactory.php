<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
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
            'name' => 'Категория ' . $i,
            'order' => $i++,
            'active' => true,
        ];
    }
}
