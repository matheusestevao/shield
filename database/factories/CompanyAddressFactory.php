<?php

namespace Database\Factories;


use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'zip_code' => $this->faker->postcode(),
            'address' => $this->faker->streetName(),
            'number_address' => $this->faker->randomDigit(),
            'complement_address' => $this->faker->secondaryAddress(),
            'neighborhood' => $this->faker->streetName(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city()
        ];
    }
}
