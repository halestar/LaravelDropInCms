<?php

use halestar\LaravelDropInCms\Enums\HeadElementType;
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
        Schema::create(config('dicms.table_prefix') . 'css_sheets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', HeadElementType::values())->default(HeadElementType::Text->value);
            $table->string('name');
            $table->string('description')->nullable();
            $table->longText('sheet')->nullable();
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
        Schema::dropIfExists(config('dicms.table_prefix') . 'css_sheets');
    }
};
