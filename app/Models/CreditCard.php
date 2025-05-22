<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $table = 'credit_cards';
    protected $primaryKey = 'card_id';

    protected $fillable = [
        'cardholder_name',
        'card_number',
        'expiration_month',
        'expiration_year',
    ];
}
