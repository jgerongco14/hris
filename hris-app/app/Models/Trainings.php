<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainings extends Model
{
    protected $table = 'trainings';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'empID',
        'empTrainName',
        'empTrainDescription',
        'empTrainFromDate',
        'empTrainToDate',
        'empTrainLocation',
        'empTrainConductedBy',
        'empTrainCertificate'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID', 'empID');
    }
}
