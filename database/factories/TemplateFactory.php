<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::title(fake()->words(rand(3, 5), true)),
            'image' => 'backgrounds/sample.jpg',
            'thumbnail' => 'thumbnails/sample.jpg',
            'properties' => [
                'title' => Str::title(fake()->words(rand(3, 5), true)),
                'subject' => Str::title(fake()->words(rand(3, 5), true)),
                'author' => 'Cassie',
                'creator' => 'Cassie',
                'keywords' => fake()->words(rand(3, 5), true),
                'orientation' => 'L',
                'unit' => 'mm',
                'format' => 'A4',
                'font' => 'Quicksand-Bold.ttf',
                'font_colour' => 'FFFFFF',
                'font_size' => 120,
                'x_offset' => 0,
                'y_offset' => -150,
            ],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Template $template) {
            $background = fake()->image(storage_path('app/backgrounds'), 3508, 2480, null, false);
            $thumbnail = fake()->image(storage_path('app/public/thumbnails'), 300, 200, null, false);

            $template->image = 'backgrounds/'.$background;
            $template->thumbnail = 'thumbnails/'.$thumbnail;
            $template->save();
        });
    }
}
