<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingAndBillingAddressIdToQuote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->integer('billing_address_id')->nullable();
            $table->integer('shipping_address_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('billing_address_id');

        });
        Schema::table('quotes', function (Blueprint $table) {
      
            $table->dropColumn('shipping_address_id');
        });
    }
}
