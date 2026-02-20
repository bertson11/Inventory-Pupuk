<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'destination')) {
                $table->string('destination')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('transactions', 'reference')) {
                $table->string('reference')->nullable()->after('destination');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['destination', 'reference']);
        });
    }
};