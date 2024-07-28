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
        Schema::table(config('dicms.table_prefix') . 'sites', function (Blueprint $table) {
            $table->foreign('header_id', 'default_header_fk')
                ->references('id')
                ->on(config('dicms.table_prefix') . 'headers')
                ->onDelete('set null');
            $table->foreign('footer_id', 'default_footer_fk')
                ->references('id')
                ->on(config('dicms.table_prefix') . 'footers')
                ->onDelete('set null');
            $table->foreign('menu_id', 'default_menu_fk')
                ->references('id')
                ->on(config('dicms.table_prefix') . 'menus')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('dicms.table_prefix') . 'sites', function (Blueprint $table) {
            $table->dropForeign('default_header_fk');
            $table->dropForeign('default_footer_fk');
            $table->dropForeign('default_menu_fk');
        });
    }
};
