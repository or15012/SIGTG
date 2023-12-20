<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id'
    ];

    public function entity_contacts(){
        return $this->hasMany(EntityContact::class, 'entity_id');
    }

    public function status(){
        if ($this->status == 1) {
            return 'Activo';
        }else if($this->status == 0){
            return 'Inactivo';
        }else{
            return 'Not defined';
        }
    }
}
