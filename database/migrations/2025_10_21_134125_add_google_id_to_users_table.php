<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable();
                $table->unique('google_id');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // SQLite tidak mendukung drop column, jadi kita buat aman
            if (Schema::hasColumn('users', 'google_id')) {
                // Tidak melakukan apa-apa untuk SQLite
                // atau bisa pakai solusi manual dengan recreate table (lebih kompleks)
            }
        });
    }
};
