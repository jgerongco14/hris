<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offices extends Model
{
    protected $table = 'offices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'officeCode',
        'officeName',
        'officeHead',
    ];


    public function officeHead()
    {
      return $this->belongsTo(Employee::class, 'officeHead', 'empID');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'officeCode', 'officeCode');
    }
}
