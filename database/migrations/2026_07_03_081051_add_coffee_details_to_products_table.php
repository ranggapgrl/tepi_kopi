<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('roast_level')->nullable()->after('description');
            $table->string('origin')->nullable()->after('roast_level');
            $table->string('weight')->nullable()->after('origin');
            $table->text('story')->nullable()->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['roast_level', 'origin', 'weight', 'story']);
        });
    }
};