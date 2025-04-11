<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpAssignment extends Model
{
    protected $table = 'empassignments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'empAssNo',
        'empID',
        'positionID',
        'empAssAppointedDate',
        'empAssEndDate',
        'officeCode',
        'departmentCode',
    ];
    public function position()
    {
        return $this->belongsTo(Position::class, 'positionID', 'positionID');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID');
    }
    public function department()
    {
        return $this->belongsTo(Departments::class, 'departmentCode', 'departmentCode');
    }
    public function office()
    {
        return $this->belongsTo(Offices::class, 'officeCode', 'officeCode');
    }
}
