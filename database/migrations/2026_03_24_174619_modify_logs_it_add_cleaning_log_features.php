<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLogsItAddCleaningLogFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs_it', function (Blueprint $table) {
            $table->text('item')->nullable()->change();
            $table->text('aktivitas')->nullable()->change();
            $table->string('unit')->nullable();
            $table->longText('ttd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs_it', function (Blueprint $table) {
            $table->string('item')->change();
            $table->string('aktivitas')->change();
            $table->dropColumn('unit');
            $table->dropColumn('ttd');
        });
    }
}
