<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // 'gradient' (preset warna) atau 'image' (gambar kustom)
            $table->string('ticket_bg_type')->default('gradient')->after('poster');
            $table->string('ticket_bg_color')->default('indigo')->after('ticket_bg_type');
            $table->string('ticket_bg_image')->nullable()->after('ticket_bg_color');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['ticket_bg_type', 'ticket_bg_color', 'ticket_bg_image']);
        });
    }
};