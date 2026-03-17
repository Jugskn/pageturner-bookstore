<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Mga Kuwento sa Maynila',
            'Lihim ng Lumang Aklatan',
            'Paglalakbay sa Kalawakan',
            'Puso sa Panahon ng Ulan',
            'Bituin sa Gabi',
            'Alamat ng Maliit na Bayan',
            'Kaibigan sa Kanto',
            'Sa Ilalim ng Puno ng Mangga',
        ];

        $descriptions = [
            'Isang nakakaantig na kuwento tungkol sa pamilya, pag-asa, at pangarap sa gitna ng lungsod.',
            'Isang lihim na nadiskubre sa loob ng lumang aklatan ang magbabago sa buhay ng isang mag-aaral.',
            'Sumama sa isang batang mangangarap na tuklasin ang kalawakan at ang misteryo ng mga bituin.',
            'Isang nobelang puno ng pag-ibig, lungkot, at pagbangon sa panahon ng unos.',
            'Kuwento ng magkakaibigang sabay-sabay hinaharap ang mga pagsubok sa kanilang paaralan.',
            'Isang simpleng araw sa baryo na puno ng tawa, laro, at mga aral sa buhay.',
        ];

        $authors = [
            'Juan Dela Cruz',
            'Maria Santos',
            'Josefa Reyes',
            'Andres Ramos',
            'Luzviminda Torres',
            'Benigno Alvarez',
        ];

        return [
            'title' => fake()->randomElement($titles),
            'author' => fake()->randomElement($authors),
            'description' => fake()->randomElement($descriptions),
            'price' => fake()->numberBetween(200, 1000),
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
