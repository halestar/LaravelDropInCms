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
        Schema::create(config('dicms.table_prefix') . 'data_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on(config('dicms.table_prefix') . 'data_items')
                ->onDelete('set null');
            $table->string('name');
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('mime')->nullable();
            $table->string('thumb')->nullable();
            $table->boolean('is_folder')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('dicms.table_prefix') . 'data_items');
    }
};
