<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Umkm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'business_type',
        'address',
        'phone',
        'description',
        'status',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'umkm_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'umkm_id');
    }

    public function staff()
    {
        return $this->hasMany(User::class, 'umkm_id')->where('role', 'staff');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'umkm_id');
    }
}
