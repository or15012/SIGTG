<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Profile extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = [
        'name',
        'description',
        'type',
        'path',
        'vision_path',
        'summary_path',
        'size_calculation_path',
        'proposal_priority',
        'status',
        'group_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }


    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
