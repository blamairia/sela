<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add ad-hoc item support to draft_sale_details table.
 * Mirrors the changes made to sale_details for draft sales.
 */
class AddAdhocFieldsToDraftSaleDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->boolean('is_adhoc')->default(false)->after('price_type');
            $table->string('adhoc_name', 255)->nullable()->after('is_adhoc');
            $table->decimal('adhoc_cost', 15, 2)->nullable()->after('adhoc_name');
            $table->decimal('adhoc_price', 15, 2)->nullable()->after('adhoc_cost');
        });
    }

    public function down()
    {
        Schema::table('draft_sale_details', function (Blueprint $table) {
            $table->dropColumn(['is_adhoc', 'adhoc_name', 'adhoc_cost', 'adhoc_price']);
        });
    }
}
