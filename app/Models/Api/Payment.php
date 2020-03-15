<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['supplier_id','product_id','category_id','amount'];

    public function category()
    {
        return $this->belongsTo('App\Models\Api\Category');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Api\Supplier');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Api\Product');
    }
}
