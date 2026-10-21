<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';

    
    protected $fillable = [
        'firstname', 'lastname', 'date_of_birth', 'education_qualification',
        'address', 'email', 'phone', 'photo', 'resume',
    ];

    
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public const QUALIFICATIONS = [
        'Diploma', 'B.E', 'B.Tech', 'B.Sc', 'BCA', 'B.Com',
        'M.E', 'M.Tech', 'M.Sc', 'MCA', 'MBA', 'Other',
    ];


    public function getFullNameAttribute(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

}
