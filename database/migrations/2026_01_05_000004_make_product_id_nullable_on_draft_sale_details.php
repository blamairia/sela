<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Make product_id nullable on draft_sale_details to support ad-hoc items.
 */
class MakeProductIdNullableOnDraftSaleDetails extends Migration
{
    public function up()
    {
        // First, drop the foreign key constraint
        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->dropForeign('draft_sale_details_product_id');
        });

        // Make product_id nullable
        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->integer('product_id')->nullable()->change();
        });

        // Re-add the foreign key with ON DELETE SET NULL
        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->foreign('product_id', 'draft_sale_details_product_id')
                  ->references('id')
                  ->on('products')
                  ->onUpdate('RESTRICT')
                  ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->dropForeign('draft_sale_details_product_id');
        });

        DB::table('draft_sale_details')->whereNull('product_id')->delete();

        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->integer('product_id')->nullable(false)->change();
        });

        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->foreign('product_id', 'draft_sale_details_product_id')
                  ->references('id')
                  ->on('products')
                  ->onUpdate('RESTRICT')
                  ->onDelete('RESTRICT');
        });
    }
}
