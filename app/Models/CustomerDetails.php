<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    protected $table = 'customer_details';
    protected $primaryKey = 'customer_details_id';

    // You might need to add this if it's not already there:
    // use App\Models\User;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'city',
        'zip_code',
        'state',
        'country',
        'user_id', // Add user_id to fillable if you plan to mass assign it
    ];

    public function user() { // Renamed from customer()
        return $this->belongsTo(User::class); // Changed to belongsTo(User::class)
    }

    // You can comment out or remove the old customer() method:
    /*
    public function customer() {
        return $this->hasOne(Customer::class, 'customer_details_id');
    }
    */
}
