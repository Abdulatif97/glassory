<?php

namespace Database\Factories;

use App\Models\Glossary;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'status' => $this->faker->numberBetween(0, 1),
            'glossary_id' => Glossary::factory(),
        ];
    }
}
