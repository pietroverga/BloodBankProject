<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nurseRole = Role::create(['name' => 'nurse']);
        $doctorRole = Role::create(['name' => 'doctor']);
        $bccAdminRole = Role::create(['name' => 'bcc_admin']);
        $hospitalAdminRole = Role::create(['name' => 'hospital_admin']);
    }
}
