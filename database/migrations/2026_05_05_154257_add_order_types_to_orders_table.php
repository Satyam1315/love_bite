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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['delivery', 'takeaway', 'dine_in'])->default('delivery')->after('total_amount');
            $table->string('table_number')->nullable()->after('address');
            $table->timestamp('pickup_time')->nullable()->after('table_number');
            $table->text('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'table_number', 'pickup_time']);
            $table->text('address')->nullable(false)->change();
        });
    }
};
