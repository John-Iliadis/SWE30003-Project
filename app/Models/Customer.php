<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Customer extends Model implements Authenticatable, CanResetPassword
{
    use AuthenticatableTrait;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'customer_details_id',
        'card_id'
    ];

    public function details() {
        return $this->belongsTo(CustomerDetails::class, 'customer_details_id');
    }

    public function creditCard() {
        return $this->belongsTo(CreditCard::class, 'card_id');
    }

    // Required for CanResetPassword
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token)
    {
        // Implementation for sending password reset notification
    }
}
