<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'employees';

    // Primary key column name
    protected $primaryKey = 'empID';

    // The attributes that are mass assignable
    protected $fillable = [
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
    ];

    // If you want to handle dates properly for 'empBirthdate'
    protected $dates = [
        'empBirthdate',
    ];
}
