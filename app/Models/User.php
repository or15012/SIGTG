<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    const TYPES = [
        '1'         => 'Estudiante',
        '2'         => 'Docente',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'first_name',
        'middle_name',
        'last_name',
        'second_last_name',
        'type',
        'carnet',
        'school_id', // Asumiendo que es la clave foránea que relaciona con la tabla 'schools'
        'modality_id'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function school()
    {
        return $this->belongsTo(School::class, 'school_id'); // Asumiendo que la clave foránea se llama 'school_id'
    }

    public function protocols()
    {
        return $this->belongsToMany(Protocol::class, 'user_protocol')
            ->withPivot('status') // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_group')
            ->withPivot(['status','is_leader']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }

    public function teacherGroups()
    {
        return $this->belongsToMany(Group::class, 'teacher_group')
            ->withPivot(['status']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }

    public function modality()
    {
        return $this->belongsTo(Modality::class, 'modality_id');
    }

}
