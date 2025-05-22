<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'customer_details_id',
        'card_id'
    ];
}
