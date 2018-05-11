<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixNoteableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->renameColumn('notable_id', 'noteable_id');
            
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->renameColumn('notable_type', 'noteable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->renameColumn('noteable_id','notable_id');
            
        });

        Schema::table('notes', function (Blueprint $table) {
            
            $table->renameColumn('noteable_type','notable_type');
        });
    }
}
