<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Part extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company_name',
        'contact_person',
        'type',
        'address',
        'vat_number',
        'tin_number',
        'phone_number',
        'email'
    ];

    // Get all purchases for this part (if supplier)
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Get all sales for this part (if customer)
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTotalDebtAttribute()
    {
        if ($this->type === 'Supplier') {
            return $this->purchases()->sum('dept');
        }
        return $this->sales()->sum('dept');
    }
}
