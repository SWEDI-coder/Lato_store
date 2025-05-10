<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MattressSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_code',
        'width',
        'length',
        'height',
        'description',
        'active'
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'height' => 'decimal:2',
        'active' => 'boolean'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getFormattedSizeAttribute()
    {
        return $this->size_code;
    }
}
