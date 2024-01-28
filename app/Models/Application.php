<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Application extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;
    protected $fillable = [
        'name',
        'status',
        'path',
        'user_id',
        'proposal_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
