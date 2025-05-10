<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'sale_price',
        'status',

        'brand_id',
        'mattress_type_id',
        'mattress_size_id',
        'is_mattress',
        'manufacture_date',
        'expiry_date',
        'barcode',
        'color',
        'warranty_period'
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'status' => 'string',
        'is_mattress' => 'boolean',
        'manufacture_date' => 'date',
        'expiry_date' => 'date'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function mattressType()
    {
        return $this->belongsTo(MattressType::class);
    }

    public function mattressSize()
    {
        return $this->belongsTo(MattressSize::class);
    }

    public function hasTransactions()
    {
        return $this->purchaseItems()->exists() || $this->saleItems()->exists();
    }

    public function getCurrentStockAttribute()
    {
        $purchasedQuantity = $this->purchaseItems()->sum('quantity');
        $soldQuantity = $this->saleItems()->sum('quantity');
        return $purchasedQuantity - $soldQuantity;
    }

    public function getLatestPurchasePriceAttribute()
    {
        return $this->purchaseItems()
            ->latest()
            ->first()
            ?->purchase_price ?? 0;
    }

    public function getLatestSalePriceAttribute()
    {
        return $this->saleItems()
            ->latest()
            ->first()
            ?->sale_price ?? 0;
    }

    public function getLatestpurchase_DiscountAttribute()
    {
        return $this->purchaseItems()
            ->latest()
            ->first()
            ?->discount ?? 0;
    }

    public function getLatestSale_DiscountAttribute()
    {
        return $this->saleItems()
            ->latest()
            ->first()
            ?->discount ?? 0;
    }

    public function getFormattedNameAttribute()
    {
        if (!$this->is_mattress) {
            return $this->name;
        }

        $brandName = $this->brand ? $this->brand->name : '';
        $typeName = $this->mattressType ? $this->mattressType->name : '';
        $sizeCode = $this->mattressSize ? $this->mattressSize->size_code : '';

        return "{$brandName} {$typeName} {$sizeCode}";
    }
}
