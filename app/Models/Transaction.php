<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'person_name',
        'part_id',
        'sale_id',
        'purchase_id',
        'type',
        'method',
        'payment_amount',
        'journal_memo',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'payment_amount' => 'decimal:2',
        'dept_paid' => 'decimal:2',
        'dept_remain' => 'decimal:2'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
