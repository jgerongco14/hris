<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'empLeaves';

    protected $fillable = [
        'empLeaveNo',
        'empID',
        'empLeaveDateApplied',
        'leaveType',
        'empLeaveStartDate',
        'empLeaveEndDate',
        'empLeaveDescription',
    ];



}
