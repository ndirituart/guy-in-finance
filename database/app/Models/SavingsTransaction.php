<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_User_id', // Make sure this matches the foreign key column name
        'amount',
        'type',
        'description',
        'current_balance',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        // Link to the User model using the custom primary key 'User_id'
        return $this->belongsTo(User::class, 'User_id', 'User_id');
    }
}

