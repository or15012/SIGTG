<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class SchoolProtocol extends Model
{
    use HasFactory;
    use LogsActivityTrait;
}
