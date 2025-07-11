<?php

namespace App\Console\Commands;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Console\Command;
use Validator;

class RegisterFacilityWithAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:facility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a facility and assign a BCC or Hospital admin to it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('-> Facility Registration');
        $facilityType = $this->choice('Facility type?', ['hospital', 'blood_collection_centre'], 0);

        $facilityData = [
            'type' => $facilityType,
            'code' => $this->ask('Facility code'),
            'name' => $this->ask('Facility name'),
            'address' => $this->ask('Address'),
            'city' => $this->ask('City'),
            'postal_code' => $this->ask('Postal code'),
            'country' => $this->ask('Country'),
            'phone' => $this->ask('Phone'),
            'email' => $this->ask('Email'),
            'website' => $this->ask('Website'),
        ];
        $adminName = $this->ask('Admin name');
        $adminEmail = $this->ask('Admin email');
        $adminPassword = $this->secret('Admin password');

        $input = array_merge($facilityData, [
            'admin_name' => $adminName,
            'admin_email' => $adminEmail,
            'admin_password' => $adminPassword,
        ]);
        $validator = Validator::make($input, [
            'code' => 'required|string|max:10|unique:facilities,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:blood_collection_centre,hospital',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:facilities,email',
            'website' => 'nullable|url|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        $facility = Facility::create($facilityData);
        $this->info("Facility " . $facility->name . " created!");

        $user = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => bcrypt($adminPassword),
            'facility_id' => $facility->id,
        ]);
        $role = $facilityType === 'bcc' ? 'hospital_admin' : 'bcc_admin';
        $user->assignRole($role);
        $this->info("Admin " . $user->email . " registered and assigned role " . $role);

        return 0;
    }

}
