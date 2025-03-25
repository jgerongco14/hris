<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveStatus extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'empLeaveStatus';

    protected $fillable = [
        'id',
        'empLSNo',
        'empLeaveNo',
        'empLSOffice',
        'empID',
        'empLSStatus',
        'empLSRemarks',
    ];
}
