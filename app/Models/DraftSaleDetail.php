<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftSaleDetail extends Model
{
    protected $fillable = [
        'id', 'date', 'draft_sale_id', 'sale_unit_id', 'quantity', 'product_id', 'total', 'product_variant_id',
        'price', 'TaxNet', 'discount', 'discount_method', 'tax_method', 'price_type',
        'is_adhoc', 'adhoc_name', 'adhoc_cost', 'adhoc_price',
    ];

    protected $casts = [
        'id' => 'integer',
        'total' => 'double',
        'quantity' => 'double',
        'draft_sale_id' => 'integer',
        'sale_unit_id' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'price' => 'double',
        'TaxNet' => 'double',
        'discount' => 'double',
        'price_type' => 'string',
        'is_adhoc' => 'boolean',
        'adhoc_cost' => 'double',
        'adhoc_price' => 'double',
    ];

    public function draftsale()
    {
        return $this->belongsTo('App\Models\DraftSale');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the display name for this line item.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->is_adhoc) {
            return $this->adhoc_name ?? 'Ad-hoc Item';
        }

        return $this->product ? $this->product->name : 'Unknown Product';
    }

    /**
     * Get the cost for profit calculations.
     */
    public function getCostForProfitAttribute()
    {
        if ($this->is_adhoc) {
            return $this->adhoc_cost ?? 0;
        }

        return $this->product ? $this->product->cost : 0;
    }
}
