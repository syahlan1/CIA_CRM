<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    use HasFactory;

    protected $fillable = ['kanban_id', 'name', 'position'];

    public function kanban()
    {
        return $this->belongsTo(Kanban::class);
    }

    public function cards()
    {
        return $this->hasMany(KanbanCard::class, 'column_id');
    }
}
