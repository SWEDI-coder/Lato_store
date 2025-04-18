<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'purchase_date',
        'part_id',
        'total_amount',
        'total_discount',
        'dept',
        'paid',
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'dept' => 'decimal:2',
        'paid' => 'decimal:2'
    ];

    // Get the supplier
    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Part::class, 'part_id')->where('type', 'Supplier');
    }
}
