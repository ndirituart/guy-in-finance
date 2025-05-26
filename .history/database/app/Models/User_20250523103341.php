<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//from Kernel
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>  // Changed from list<string> to array<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'birthday',
        'phone_number',
        'password',
        // 'middle_name', // Removed from here
        // 'id_number',   // Removed from here
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>  // Changed from list<string> to array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'date', // Added 'birthday' casting
    ];

    /**
     * @var array<string>
     */
    
}

// End of User model
