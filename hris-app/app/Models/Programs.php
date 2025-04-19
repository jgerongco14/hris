<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programs extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'programCode',
        'programName',
        'created_at',
        'updated_at',
    ];

    public function departments()
    {
        return $this->belongsToMany(Departments::class, 'department_program', 'program_id', 'department_id')->withPivot('created_at', 'updated_at');;
    }

    public function empAssignments()
    {
        return $this->hasMany(EmpAssignment::class, 'programCode', 'programCode');
    }
}
