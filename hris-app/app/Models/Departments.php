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

    public function program()
    {
        return $this->hasMany(Programs::class, 'programCode', 'programCode');
    }

    public function departmentHead()
    {
      return $this->belongsTo(Employee::class, 'departmentHead', 'empID');
    }
}
