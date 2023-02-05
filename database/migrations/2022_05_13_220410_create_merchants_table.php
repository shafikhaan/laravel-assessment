<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('domain')->unique();
            $table->string('display_name');
            $table->boolean('turn_customers_into_affiliates')->default(true);
            //  In simple words, Float point numbers are imprecise because they are approximate while Decimal are exact that's because Decimal is more appropriate then float,
            $table->decimal('default_commission_rate', 10, 30)->default(0.1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchants');
    }
};
