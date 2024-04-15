<?php

namespace Database\Seeders;

use App\Models\TypeAddress;
use Illuminate\Database\Seeder;

class TypeAddressSeeder extends Seeder
{
    protected $types = [
        'Fábrica / Operacao',
        'Comercial',
        'Financeiro',
        'Correspondência',
        'Remessa/Logística'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $type) {
            TypeAddress::create([
                'name' => $type
            ]);
        }
    }
}
