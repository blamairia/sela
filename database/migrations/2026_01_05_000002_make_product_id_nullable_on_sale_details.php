<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Make product_id nullable on sale_details to support ad-hoc items.
 * Ad-hoc items don't have a product record, so product_id must be nullable.
 */
class MakeProductIdNullableOnSaleDetails extends Migration
{
    public function up()
    {
        // First, drop the foreign key constraint
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropForeign('sale_product_id');
        });

        // Make product_id nullable
        Schema::table('sale_details', function (Blueprint $table) {
            $table->integer('product_id')->nullable()->change();
        });

        // Re-add the foreign key with ON DELETE SET NULL
        Schema::table('sale_details', function (Blueprint $table) {
            $table->foreign('product_id', 'sale_product_id')
                  ->references('id')
                  ->on('products')
                  ->onUpdate('RESTRICT')
                  ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        // Drop the foreign key
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropForeign('sale_product_id');
        });

        // Delete any rows with null product_id (ad-hoc items) before making non-nullable
        DB::table('sale_details')->whereNull('product_id')->delete();

        // Make product_id required again
        Schema::table('sale_details', function (Blueprint $table) {
            $table->integer('product_id')->nullable(false)->change();
        });

        // Re-add the original foreign key
        Schema::table('sale_details', function (Blueprint $table) {
            $table->foreign('product_id', 'sale_product_id')
                  ->references('id')
                  ->on('products')
                  ->onUpdate('RESTRICT')
                  ->onDelete('RESTRICT');
        });
    }
}
