<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bloodSamples()
    {
        return $this->hasMany(BloodSample::class);
    }

    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class);
    }

    public function isHospital(): bool
    {
        return $this->type === 'hospital';
    }

    public function isBloodCollectionCentre(): bool
    {
        return $this->type === 'blood_collection_centre';
    }
}
