<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Project::class;


    public function definition()
    {
        return [
            'name' => $this->faker->realText(23),
            'desc' => $this->faker->realText(100),
            'img' => 'https://via.placeholder.com/300'
        ];
    }
}
