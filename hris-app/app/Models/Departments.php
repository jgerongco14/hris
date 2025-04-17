<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'departmentCode',
        'departmentName',
        'created_at',
        'updated_at',
    ];


    public function programs()
    {
        return $this->belongsToMany(Programs::class, 'department_program', 'department_id', 'program_id');
    }

    public function empAssignments()
    {
        return $this->hasMany(Employee::class, 'departmentCode', 'departmentCode');
    }
}
