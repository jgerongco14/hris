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
    ];

    public function departments()
    {
        return $this->belongsTo(Departments::class, 'programCode', 'programCode');
    }
}
