<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanPermission extends Model
{
    use HasFactory;

    protected $fillable = ['kanban_id', 'user_id', 'role'];

    public function kanban()
    {
        return $this->belongsTo(Kanban::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
