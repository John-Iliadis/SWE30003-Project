<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function customerDetails()
    {
        return CustomerDetails::find($this['customer_details_id']);
    }

    public function creditCard()
    {
        return CreditCard::find($this['card_id']);
    }
}
