<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'empID',
        'semester',
        'year',
        'reason',
        'attachments',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID', 'empID');
    }
}
