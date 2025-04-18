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

    protected $casts = [
        'children' => 'array',
    ];


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
        'empCivilStatus',
        'empBloodType',
        'empContactNo',
        'empRVMRetirementNo',
        'empBPIATMAccountNo',
        'empDateHired',
        'empDateResigned',
        'empPersonelStatus',
        'empEmployeerName',
        'empEmployeerAddress',
        'empFatherName',
        'empMotherName',
        'empSpouseName',
        'empSpouseBdate',
        'empChildrenName',
        'empChildrenBdate',
        'empEmergencyContactName',
        'empEmergencyContactAddress',
        'empEmergencyContactNo',
        'status',
    ];

    public function getChildrenAttribute()
    {
        $names = json_decode($this->empChildrenName ?? '[]', true);
        $birthdates = json_decode($this->empChildrenBdate ?? '[]', true);

        $children = [];
        foreach ($names as $i => $name) {
            $children[] = [
                'name' => $name,
                'birthdate' => $birthdates[$i] ?? null,
            ];
        }

        return $children;
    }


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

    public function office()
    {
        return $this->hasMany(Offices::class, 'officeHead', 'empID');
    }

    public function reports()
    {
        return $this->hasMany(Reports::class, 'empID', 'empID');
    }

    public function trainings()
    {
        return $this->hasMany(Trainings::class, 'empID', 'empID');
    }
    public function isAssignedToOfficeByName($officeName)
    {
        return $this->assignments()
            ->whereHas('office', function ($query) use ($officeName) {
                $query->where('officeName', $officeName);
            })
            ->exists();
    }
}
