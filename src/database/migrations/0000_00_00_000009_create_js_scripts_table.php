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
        Schema::create(config('dicms.table_prefix') . 'js_scripts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')->references('id')->on(config('dicms.table_prefix') . 'sites')->onDelete('cascade');
            $table->enum('type', [\halestar\LaravelDropInCms\Enums\HeadElementType::Link->value, \halestar\LaravelDropInCms\Enums\HeadElementType::Text->value]);
            $table->string('name');
            $table->string('description')->nullable();
            $table->longText('script')->nullable();
            $table->string('href')->nullable();
            $table->string('link_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('dicms.table_prefix') . 'js_scripts');
    }
};
