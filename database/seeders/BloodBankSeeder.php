<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facility;
use App\Models\BloodSample;
use App\Models\SampleRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class BloodBankSeeder extends Seeder
{
    public function run()
    {
        // Facilities
        $hospital = Facility::create([
            'code' => 'HSP001',
            'name' => 'Central Hospital',
            'type' => 'hospital',
            'address' => '123 Main St',
            'city' => 'Healthville',
            'postal_code' => '10000',
            'country' => 'USA',
            'phone' => '1234567890',
            'email' => 'hospital@example.com',
            'website' => 'https://hospital.example.com',
        ]);

        $collectionCentre = Facility::create([
            'code' => 'BCC001',
            'name' => 'City Blood Center',
            'type' => 'blood_collection_centre',
            'address' => '456 Blood Dr',
            'city' => 'Donor City',
            'postal_code' => '20000',
            'country' => 'USA',
            'phone' => '0987654321',
            'email' => 'bloodcenter@example.com',
            'website' => 'https://bloodcenter.example.com',
        ]);

        // Roles
        $nurseRole = Role::findByName('nurse');
        $doctorRole = Role::findByName('doctor');
        $bccAdminRole = Role::findByName('bcc_admin');
        $hospitalAdminRole = Role::findByName('hospital_admin');

        // Users
        $nurse = User::create([
            'name' => 'Nina A.',
            'email' => 'nina@nurse.com',
            'password' => Hash::make('password'),
            'facility_id' => $collectionCentre->id,
        ]);
        $nurse->assignRole($nurseRole);

        $doctor = User::create([
            'name' => 'Daniel B.',
            'email' => 'daniel@doctor.com',
            'password' => Hash::make('password'),
            'facility_id' => $hospital->id,
        ]);
        $doctor->assignRole($doctorRole);

        $hospitalAdmin = User::create([
            'name' => 'Peter',
            'email' => 'peter@hospital.com',
            'password' => Hash::make('password'),
            'facility_id' => $hospital->id,
        ]);
        $hospitalAdmin->assignRole($hospitalAdminRole);

        $bccAdmin = User::create([
            'name' => 'Gabriel',
            'email' => 'gabriel@bcc.com',
            'password' => Hash::make('password'),
            'facility_id' => $collectionCentre->id,
        ]);
        $bccAdmin->assignRole($bccAdminRole);

        // Blood Samples
        $samples = [
            [
                'facility_id' => $collectionCentre->id,
                'nurse_id' => $nurse->id,
                'blood_type' => 'A',
                'rh_factor' => '+',
                'volume_ml' => 500,
                'collection_date' => now()->subDays(2),
                'expiration_date' => now()->addDays(28),
                'status' => 'requested',
                'notes' => 'Collected without issue.',
            ],
            [
                'facility_id' => $collectionCentre->id,
                'nurse_id' => $nurse->id,
                'blood_type' => 'B',
                'rh_factor' => '-',
                'volume_ml' => 450,
                'collection_date' => now()->subDays(5),
                'expiration_date' => now()->addDays(25),
                'status' => 'available',
                'notes' => 'Urgent request pending.',
            ],
            [
                'facility_id' => $collectionCentre->id,
                'nurse_id' => $nurse->id,
                'blood_type' => 'AB',
                'rh_factor' => '+',
                'volume_ml' => 480,
                'collection_date' => now()->subDays(1),
                'expiration_date' => now()->addDays(29),
                'status' => 'available',
                'notes' => 'Ready for donation, without issues.',
            ],
            [
                'facility_id' => $collectionCentre->id,
                'nurse_id' => $nurse->id,
                'blood_type' => 'O',
                'rh_factor' => '-',
                'volume_ml' => 500,
                'collection_date' => now()->subDays(3),
                'expiration_date' => now()->addDays(27),
                'status' => 'available',
                'notes' => 'Ready for donation.',
            ],
        ];

        foreach ($samples as $sampleData) {
            BloodSample::create($sampleData);
        }

        // Sample Requests
        $firstSample = BloodSample::first();
        SampleRequest::create([
            'doctor_id' => $doctor->id,
            'facility_id' => $hospital->id,
            'blood_sample_id' => $firstSample->id,
            'status' => 'pending',
            'notes' => 'Urgent request for surgery.',
        ]);
    }
}
