<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;

class Withdrawal extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;
    protected $fillable = [
        'group_id',
        'user_id',
        'type_withdrawals_id',
        'description',
        'status',
        'withdrawal_request_path',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function type_withdrawal()
    {
        return $this->belongsTo(TypeWithdrawal::class, 'type_withdrawals_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(Group::class, 'user_id');
    }

    public function status()
    {
        switch ($this->status) {
            case 0:
                return 'Presentada';
                break;
            case 1:
                return 'Aprobado';
                break;
            case 2:
                return 'Rechazado';
                break;
            default:
                return 'Not found';
                break;
        }
    }
}
