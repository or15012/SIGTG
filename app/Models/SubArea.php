<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubArea extends Model
{
    use HasFactory;

    protected $table = 'subarea';

    public function area(){
        return $this->belongsTo(Area::class, 'area_id');
    }

    
}
