<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleRequest extends Model
{
    protected $fillable = [
        'doctor_id', // who requested
        'facility_id', // who requested
        'blood_sample_id',
        'status', // 'pending', 'approved', 'denied'
        'notes',
        'track_number'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class, 'blood_sample_id');
    }
}
