<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\Schema(
 *      schema="User",
 *      required={"id", "name", "email", "status"},
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="email", type="string", example="john@example.com"),
 *      @OA\Property(property="phone_number", type="string", example="0123456789"),
 *      @OA\Property(property="status", type="string", example="active"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:30:00Z"),
 *      @OA\Property(property="deleted_at", type="string", format="date-time", example="2023-01-01T12:30:00Z")
 * )
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'deleted_at' => 'datetime'
        ];
    }
}
