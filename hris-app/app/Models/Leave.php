<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'empLeaves';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $casts = [
        'empLeaveAttachment' => 'array',
    ];


    protected $fillable = [
        'id',
        'empLeaveNo',
        'empID',
        'empLeaveDateApplied',
        'leaveType',
        'empLeaveStartDate',
        'empLeaveEndDate',
        'empLeaveDescription',
        'empLeaveAttachment',
    ];

    public function status()
    {
        return $this->hasOne(LeaveStatus::class, 'empLeaveNo', 'empLeaveNo');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID', 'empID');
    }
}
