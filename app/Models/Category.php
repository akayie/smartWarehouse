<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
use HasFactory, SoftDeletes; // တစ်ကြောင်းတည်း ရေးလို့ရပါတယ်

    protected $table = 'categories';
    protected $fillable = [
        'name',
        'image'
    ];

     /**
     * Category has many Items.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
