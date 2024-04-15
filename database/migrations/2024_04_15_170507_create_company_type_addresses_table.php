<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTypeAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_type_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_address_id');
            $table->foreign('company_address_id')->references('id')->on('company_addresses')->onDelete('cascade');
            $table->uuid('type_address_id');
            $table->foreign('type_address_id')->references('id')->on('type_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_type_addresses');
    }
}
