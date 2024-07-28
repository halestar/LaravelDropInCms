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
        Schema::create(config('dicms.table_prefix') . 'sites_js_scripts', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')->references('id')->on(config('dicms.table_prefix') . 'sites')->onDelete('cascade');
            $table->unsignedBigInteger('script_id');
            $table->foreign('script_id')->references('id')->on(config('dicms.table_prefix') . 'js_scripts')->onDelete('cascade');
            $table->tinyInteger('order_by')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('dicms.table_prefix') . 'sites_js_scripts');
    }
};
