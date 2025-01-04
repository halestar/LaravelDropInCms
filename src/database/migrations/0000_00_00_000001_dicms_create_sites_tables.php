<?php

use halestar\LaravelDropInCms\Enums\WrapperTagType;
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
        Schema::create(config('dicms.table_prefix') . 'sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('title')->nullable();
            $table->string('body_attr')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('archived')->default(false);
            $table->unsignedBigInteger('header_id')->nullable();
            $table->unsignedBigInteger('footer_id')->nullable();
            $table->string('homepage_url')->nullable();
            $table->string('favicon')->nullable();
            $table->enum('tag', WrapperTagType::values())->nullable()->default(null);
            $table->text('options')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('dicms.table_prefix') . 'sites');
    }
};
