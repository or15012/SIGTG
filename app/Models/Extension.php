<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extension extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'type_extension_id',
        'description',
        'status',
        'extension_request_path',
        'schedule_activities_path',
        'approval_letter_path',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    public function type_extension()
    {
        return $this->belongsTo(TypeExtension::class, 'type_extension_id');
    }

    public function status(){
        switch ($this->status) {
            case 0:
                return 'Presentado';
                break;
            case 1:
                return 'Aprobado';
                break;
            case 0:
                return 'Rechazado';
                break;
            default:
                return 'Not found';
                break;
        }
    }
}
