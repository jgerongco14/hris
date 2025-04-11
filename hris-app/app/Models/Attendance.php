<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'empAttendances';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'empID',
        'empAttID',
        'empAttDate',
        'empAttTimeIn',
        'empAttBreakOut',
        'empAttBreakIn',
        'empAttTimeOut',
        'empAttRemarks'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID', 'empID');
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'empID', 'empID');
    }
    
}
