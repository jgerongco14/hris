<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $table = 'empContributions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'empConNo',
        'empID',
        'empContype',
        'empConAmount',
        'employeerContribution',
        'empPRNo',
        'empConDate',
       
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID', 'empID');
    }
}
