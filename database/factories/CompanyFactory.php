<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\TypeAddress;
use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_name' => $this->faker->company(),
            'trade_name' => $this->faker->company(),
            'corporate_registry_number' => $this->faker->unique()->randomFloat()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Company $company) {
            $address = CompanyAddress::factory()->create(['company_id' => $company->id]);

            $address->type()->create([
                'type_address_id' => TypeAddress::factory()->create()->id
            ]);
        });
    }
}
