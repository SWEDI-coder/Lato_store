<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'sale_date',
        'part_id',
        'total_amount',
        'total_discount',
        'dept',
        'paid',
        'efd_number',
        'z_number',
        'receipt_number',
        'barcode_text',
        'status'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'dept' => 'decimal:2',
        'paid' => 'decimal:2'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function customer()
    {
        return $this->belongsTo(Part::class, 'part_id')->where('type', 'Customer');
    }
}
