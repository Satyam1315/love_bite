<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed defaults
        DB::table('settings')->insert([
            ['key' => 'opening_time', 'value' => '13:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'closing_time', 'value' => '22:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'is_manually_closed', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'closed_message', 'value' => 'We are currently closed. We open at {opening_time} and close at {closing_time}.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
