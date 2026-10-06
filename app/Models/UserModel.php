<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'reset_token',
        'reset_expires',
        'email_verification_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;   // auto-manage created_at & updated_at
    protected $dateFormat    = 'datetime';
}
