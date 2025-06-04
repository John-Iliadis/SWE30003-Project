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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the customer details associated with the user.
     */
    public function details()
    {
        return $this->hasOne(CustomerDetails::class); // Assuming 'user_id' in 'customer_details' table
    }

    /**
     * Get the credit card associated with the user.
     */
    public function creditCard()
    {
        return $this->hasOne(CreditCard::class); // Assuming 'user_id' in 'credit_cards' table
    }

    /**
     * Get the orders associated with the user through customer details.
     */
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            CustomerDetails::class,
            'user_id', // Foreign key on customer_details table
            'customer_details_id', // Foreign key on orders table
            'id', // Local key on users table
            'customer_details_id' // Local key on customer_details table
        );
    }

    // This relationship is likely not needed anymore as we're using User->details directly
    // public function customer()
    // {
    //     return $this->hasOne(Customer::class, 'user_id', 'id');
    // }
}