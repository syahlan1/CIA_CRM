<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardHistories extends Model
{
    protected $table = 'card_histories';
    public $timestamps = false; // karena kita menggunakan field "created_date" sendiri

    protected $fillable = [
        'card_id', 'column_id', 'position', 'updated_value', 'created_date', 'created_by'
    ];

    // Relationship ke card
    public function card()
    {
        return $this->belongsTo(KanbanCard::class, 'card_id');
    }

    // Relationship ke column
    public function column()
    {
        return $this->belongsTo(KanbanColumn::class, 'column_id');
    }

    // Relationship ke user (pembuat history, alias user yang mengupdate)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
