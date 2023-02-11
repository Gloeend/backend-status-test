<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'painting', 'film', 'handler', 'width', 'height', 'opening', 'accessories', 'price'
    ];

}
