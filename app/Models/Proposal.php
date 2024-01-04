<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',,
        'path',
        'amount_student',
        'entity_id',
        'status',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }


}
