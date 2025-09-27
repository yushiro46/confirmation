<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categoryId = $this->faker->numberBetween(1, 5);

        $topics = [
            1 => '商品のお届けについて',
            2 => '商品の交換について',
            3 => '商品トラブル',
            4 => 'ショップへのお問い合わせ',
            5 => 'その他'
        ];

        $base = $topics[$categoryId];

        $hasBuilding = $this->faker->boolean(50);

        $building = $hasBuilding
            ? $this->faker->secondaryAddress()
            : null;

        return [
            'category_id' => $categoryId,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement([
                '男性',
                '女性',
                'その他'
            ]),
            'email' => $this->faker->unique()->safeEmail(),
            'tel' => $this->faker->phoneNumber(),
            'address' => $this->faker->prefecture . $this->faker->city . $this->faker->streetAddress,
            'building' => $building,
            'detail' => $base . 'に関して' . $this->faker->realText(50),
        ];
    }
}
