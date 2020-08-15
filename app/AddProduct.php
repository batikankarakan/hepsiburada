<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddProduct extends Model
{
    protected $fillable = [
        'user_id',
        'Name',
        'ImageLink',
        'Price',
    ];
}
