<?php

namespace Database\Factories;

use App\Models\TypeAddress;
use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyTypeAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type_address_id' => TypeAddress::factory()->create()->id,
            'company_address_id' => CompanyAddress::factory()->create()->id
        ];
    }
}
