<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanCard extends Model
{
    use HasFactory;

    protected $fillable = ['column_id', 'title', 'description', 'position', 'created_by', 'updated_by'];

    public function column()
    {
        return $this->belongsTo(KanbanColumn::class);
    }

    public function values()
    {
        return $this->hasMany(KanbanCardValue::class, 'card_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
