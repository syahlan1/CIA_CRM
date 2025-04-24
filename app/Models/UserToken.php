<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    protected $table = 'user_tokens';
    protected $fillable = ['user_id', 'token', 'device', 'location', 'status'];
}
