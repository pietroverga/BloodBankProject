<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodSample extends Model
{
    protected $fillable = [
        'facility_id',
        'nurse_id',
        'blood_type',
        'rh_factor',
        'volume_ml',
        'collection_date',
        'expiration_date',
        'status', // 'available', 'requested', 'used'
        'notes'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function sampleRequest()
    {
        return $this->hasOne(SampleRequest::class);
    }
}
