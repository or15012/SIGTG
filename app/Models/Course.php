<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Course extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = ['name', 'description', 'cycle_id', 'school_id'];

    public function teacher_courses()
    {
        return $this->belongsToMany(User::class, 'teacher_courses')
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function cycle(){
        return $this->belongsTo(Cycle::class, 'cycle_id')->withDefault();
    }

    public function school(){
        return $this->belongsTo(School::class, 'school_id')->withDefault();
    }

    public function preregistrations()
    {
        return $this->belongsToMany(User::class, 'course_preregistrations')
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function registrations()
    {
        return $this->belongsToMany(User::class, 'course_registrations')
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
