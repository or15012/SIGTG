<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Consulting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'topics',
        'number',
        'summary',
        'date',
        'group_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relación con el modelo group
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
