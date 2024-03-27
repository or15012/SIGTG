<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class SubArea extends Model
{
    use HasFactory;
    use LogsActivityTrait;
    protected $table = "subareas";
    protected $fillable = [
        'id',
        'name',
        'area_id',
    ];

    public function area(){
        return $this->belongsTo(Area::class, 'area_id');
    }


}
