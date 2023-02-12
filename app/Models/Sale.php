<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'product_id','quantity','total_price', 'salePharma_id','price'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    // public function product_name(){
    // 	return $this->belongsTo(Product::class,'product_id','id');
    // }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }
}
