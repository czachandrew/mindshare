<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->date('good_until');
            $table->integer('company_id');
            $table->integer('lead_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('owner_id');
            $table->decimal('value', 8, 2);
            $table->string('status')->nullable();
            $table->integer('shipping_address_1')->nullable();
            $table->integer('billing_address_1')->nullable();
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
        Schema::dropIfExists('quotes');
    }
}
