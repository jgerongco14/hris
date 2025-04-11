<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'departmentCode',
        'departmentName',
        'departmentHead',
        'programCode'
    ];

    public function programs()
    {
        return $this->belongsToMany(Programs::class, 'department_program', 'department_id', 'program_id');
    }

    public function departmentHead()
    {
        return $this->belongsTo(Employee::class, 'departmentHead', 'empID');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'departmentCode', 'departmentCode');
    }

    
}
