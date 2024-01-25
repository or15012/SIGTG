<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Proposal extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = [
        'name',
        'description',
        'path',
        'amount_student',
        'entity_id',
        'protocol_id',
        'user_id',
        'status',
        'school_id',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function status(){
        if ($this->status == 1) {
            return 'Aprobado';
        }else if($this->status == 0){
            return 'No aprobado';
        }else{
            return 'Not defined';
        }
    }

}
