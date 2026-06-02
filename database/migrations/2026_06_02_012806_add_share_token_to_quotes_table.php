<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('share_token', 64)->nullable()->after('notes');
        });

        \Illuminate\Support\Facades\DB::table('quotes')->orderBy('id')->chunk(100, function ($quotes) {
            foreach ($quotes as $quote) {
                \Illuminate\Support\Facades\DB::table('quotes')
                    ->where('id', $quote->id)
                    ->update(['share_token' => \Illuminate\Support\Str::random(32)]);
            }
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->string('share_token', 64)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('share_token');
        });
    }
};
