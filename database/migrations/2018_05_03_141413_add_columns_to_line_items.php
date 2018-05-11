<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToLineItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('line_items', function (Blueprint $table) {
            $table->integer('part_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('sale_price', 8 ,2)->nullable(); 
            $table->integer('lineable_id')->nullable();
            $table->string('lineable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('line_items', function (Blueprint $table) {
            $table->dropColumn('part_id');

        });

        Schema::table('line_items', function (Blueprint $table) {

            $table->dropColumn('quantity');

        });

        Schema::table('line_items', function (Blueprint $table) {

            $table->dropColumn('sale_price');

        });

        Schema::table('line_items', function (Blueprint $table) {

            $table->dropColumn('lineable_id');

        });

        Schema::table('line_items', function (Blueprint $table) {

            $table->dropColumn('lineable_type');
        });
    }
}
