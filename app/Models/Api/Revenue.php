<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $fillable = ['client_id', 'fee_id', 'amount','description'];

    public function clients()
    {
        return $this->belongsTo('App\Models\Api\Client');
    }
    public function fees()
    {
        return $this->belongsTo('App\Models\Api\Fee');
    }
}
