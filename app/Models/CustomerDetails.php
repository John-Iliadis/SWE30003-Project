<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    protected $table = 'customer_details';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'city',
        'zip_code',
        'state',
        'country',
    ];
}
