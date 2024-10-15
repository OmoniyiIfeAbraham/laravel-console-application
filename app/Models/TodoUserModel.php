<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoUserModel extends Model
{
    use HasFactory;

    protected $table = 'todo_users';

    protected $fillable = ['username', 'email', 'password'];
}
