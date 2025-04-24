<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanInvite extends Model
{
    use HasFactory;

    protected $fillable = ['kanban_id', 'invite_token', 'role'];

    public function kanban()
    {
        return $this->belongsTo(Kanban::class);
    }
}
