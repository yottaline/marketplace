<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_code',
        'user_name',
        'user_email',
        'user_type',
        'user_password',
        'user_created'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_password' => 'hashed',
    ];


    static function fetch($id = 0, $params = null, $limit = 1, $lastId = null)
    {
        $users = self::limit($limit);
        if ($lastId) $users->where('id', '<', $lastId);

        if (isset($params['q'])) {
            $users->where(function (Builder $query) use ($params) {
                $query->where('user_code', 'like', '%' . $params['q'] . '%')
                    ->orWhere('user_email', 'like', '%' . $params['q'] . '%')
                    ->orWhere('user_name', $params['q']);
            });

            unset($params['q']);
        }

        if ($params) $users->where($params);
        if ($id) $users->where('id', $id);

        return $id ? $users->first() : $users->get();
    }

}