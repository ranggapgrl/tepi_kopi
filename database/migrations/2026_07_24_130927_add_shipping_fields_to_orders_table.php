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
            $table->string('destination_id')->nullable()->after('shipping_address');
            $table->string('destination_label')->nullable()->after('destination_id');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('destination_label');
            $table->string('courier_service')->nullable()->after('courier'); // ex: REG, YES, OKE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
