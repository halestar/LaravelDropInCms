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
        Schema::create(config('dicms.table_prefix') . 'menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')->references('id')->on(config('dicms.table_prefix') . 'sites')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('menu')->nullable();
            $table->string('nav_classes')->nullable();
            $table->string('container_classes')->nullable();
            $table->string('element_classes')->nullable();
            $table->string('link_classes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('dicms.table_prefix') . 'menus');
    }
};
