<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date');

            $table->string('account');
            $table->string('symbol');
            $table->string('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('price_change', 10, 2)->nullable();
            $table->decimal('value', 10, 2);
            $table->decimal('today_gain_dollar', 10, 2)->nullable();
            $table->decimal('today_gain_percent', 5, 2)->nullable();
            $table->decimal('total_gain_dollar', 10, 2)->nullable();
            $table->decimal('total_gain_percent', 5, 2)->nullable();
            $table->decimal('percent_of_account', 5, 2)->nullable();
            $table->decimal('cost_basis', 10, 2)->nullable();
            $table->decimal('cost_basis_per_share', 10, 2)->nullable();
            $table->string('type');

            $table->unique(['account', 'symbol', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
