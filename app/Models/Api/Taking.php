<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Taking extends Model
{
    protected $fillable = ['customer_id', 'fee_id', 'amount'];

    public function customers()
    {
        return $this->belongsTo('App\Models\Api\Customer');
    }
    public function fees()
    {
        return $this->belongsTo('App\Models\Api\Fee');
    }
}
