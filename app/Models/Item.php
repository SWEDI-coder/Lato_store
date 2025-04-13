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
        'status'
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'status' => 'string'
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

    public function getLatestDiscountAttribute()
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
}
