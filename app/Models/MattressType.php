<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MattressType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
