<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'admin_user',
        'admin_status'
    ];

    static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $users = self::join('users', 'admin_user', 'id')->where('user_type', 1)->orderBy('user_created', 'DESC');

        if($params) $users->where($params);
        if($lastId) $users->where('admin_id', '<', $lastId);
        if($id) $users->where('admin_id', $id);
        return $id ? $users->first() : $users->get()->all();
    }

    static function submit($param, $id)
    {
        if($id) return User::where('id', $id)->update($param) ? $id : false;
        $status = User::create($param);

        if($status){
            self::create(['admin_user' => $status->id]);
            return $status->id;
        }
        return false;
    }
}