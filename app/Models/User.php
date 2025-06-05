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
        return $this->belongsTo(CustomerDetails::class, 'customer_details_id', 'customer_details_id');
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class, 'card_id', 'card_id');
    }
}
