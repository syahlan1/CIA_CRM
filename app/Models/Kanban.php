<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // pastikan untuk mengimpor

class Kanban extends Model
{
    use HasFactory;

    // Karena kita menggunakan UUID, nonaktifkan auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['user_id', 'title', 'type', 'is_shared'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function columns()
    {
        return $this->hasMany(KanbanColumn::class, 'kanban_id');
    }

    public function permissions()
    {
        return $this->hasMany(KanbanPermission::class, 'kanban_id');
    }
}
