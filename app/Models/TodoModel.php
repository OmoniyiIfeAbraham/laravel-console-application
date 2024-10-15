<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_todo';

    protected $fillable = ['title', 'description', 'todo_users_id'];

    public function users() {
        return $this->belongsTo(TodoUserModel::class, 'todo_users_id');
    }
}
