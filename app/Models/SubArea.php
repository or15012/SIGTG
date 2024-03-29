<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class SubArea extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'subarea';

    public function area(){
        return $this->belongsTo(Area::class, 'area_id');
    }

    
}
