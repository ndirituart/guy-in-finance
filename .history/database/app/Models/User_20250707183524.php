<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Ensure this matches your actual table name

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'User_id'; // <--- CRUCIAL: Tell Eloquent your primary key name

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    // If your User_id is an auto-incrementing integer, you can remove this line.
    // If it's a UUID or string you generate, keep it as 'string'.
    // Based on your migration `id` bigint unsigned not null auto_increment primary key,
    // it seems it's still an auto-incrementing integer, so you might not need this line
    // or set it to 'integer'. Let's assume it's an integer for now.
    // protected $keyType = 'string'; // Remove or set to 'integer' if it's an auto-incrementing integer.

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    // If your User_id is auto-incrementing, keep this as true.
    // If you manually assign User_id (e.g., UUIDs), set to false.
    public $incrementing = true; // <--- Keep this as true if it's auto-incrementing

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'PIN', //which is added on a separate page after registration
        'birthday',
        'id_number',
        'phone_number',
        'password',
        'loan_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'PIN',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'PIN' => 'hashed',
    ];

    // If you had a custom accessor for 'id', you might need to adjust it
    // public function getIdAttribute()
    // {
    //     return $this->attributes['User_id'];
    // }
}
