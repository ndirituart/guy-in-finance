<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'requested_on',
        'last_updated',
    ];

    // Because of the foreign key of user_id from Users table, we define the relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
