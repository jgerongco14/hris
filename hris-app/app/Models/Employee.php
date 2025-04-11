<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Leave;

class Employee extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'employees';

    // Primary key column name
    protected $primaryKey = 'id';

    // The attributes that are mass assignable
    protected $fillable = [
        'id',
        'empID',
        'user_id',
        'empPrefix',
        'empSuffix',
        'empFname',
        'empMname',
        'empLname',
        'empGender',
        'empBirthdate',
        'address',
        'province',
        'city',
        'barangay',
        'empSSSNum',
        'empTinNum',
        'empPagIbigNum',
        'photo',
        'role',
    ];

    // If you want to handle dates properly for 'empBirthdate'
    protected $dates = [
        'empBirthdate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'empID', 'empID');
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'empID', 'empID');
    }

    public function assignments()
    {
        return $this->hasMany(EmpAssignment::class, 'empID', 'empID');
    }
    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'empID', 'empID');
    }

    public function hasPosition(array $positions)
    {
        return $this->assignments()->with('position')->get()->contains(function ($assignment) use ($positions) {
            return in_array($assignment->position?->positionName, $positions);
        });
    }

    public function department()
    {
        return $this->hasMany(Departments::class, 'departmentHead', 'empID');
    }

   
}
