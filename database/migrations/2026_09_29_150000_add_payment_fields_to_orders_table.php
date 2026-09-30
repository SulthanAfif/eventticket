<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->timestamp('expires_at')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'expires_at']);
        });
    }
};
