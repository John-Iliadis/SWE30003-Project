<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CustomerDetails;
use App\Models\CreditCard;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'customer_details_id',
        'card_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

//    public function details()
//    {
//        return $this->hasOne(CustomerDetails::class); // Assuming 'user_id' in 'customer_details' table
//    }
//
//    public function creditCard()
//    {
//        return $this->hasOne(CreditCard::class); // Assuming 'user_id' in 'credit_cards' table
//    }
//
//    public function orders()
//    {
//        return $this->hasManyThrough(
//            Order::class,
//            CustomerDetails::class,
//            'user_id', // Foreign key on customer_details table
//            'customer_details_id', // Foreign key on orders table
//            'id', // Local key on users table
//            'customer_details_id' // Local key on customer_details table
//        );
//    }
}
