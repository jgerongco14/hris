<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmpAssignment;

class Position extends Model
{

    protected $table = 'positions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'positionID',
        'positionName',
        'positionDescription',
    ];

    public function empAssignments()
    {
        return $this->hasMany(EmpAssignment::class, 'positionID', 'positionID');
    }

}
