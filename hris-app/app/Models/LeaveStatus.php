<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveStatus extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'empLeaveStatus';
    protected $primaryKey = 'empLeaveNo'; // or whatever your PK column is named


    protected $fillable = [
        'empLSNo',
        'empLeaveNo',
        'empLSOffice',
        'empID',
        'empLSStatus',
        'empLSRemarks',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class, 'empLeaveNo', 'empLeaveNo');
    }
}
