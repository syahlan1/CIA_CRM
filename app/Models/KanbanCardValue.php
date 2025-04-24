<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanCardValue extends Model
{
    protected $fillable = ['card_id', 'key', 'value'];

    public function card()
    {
        return $this->belongsTo(KanbanCard::class);
    }
}

