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
        'user_id',
        'sale_id',
        'purchase_id',
        'type',
        'method',
        'payment_amount',
        'dept_paid',
        'dept_remain',
        'journal_memo',
        'transaction_date'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
