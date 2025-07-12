<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BloodSample;
use Illuminate\Support\Facades\Auth;

class BCCAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        $samplesQuery = BloodSample::where('facility_id', $facilityId)
            ->where('status', 'available');
        $samplesNr = (clone $samplesQuery)->count();
        $expiredSamples = (clone $samplesQuery)->where('expiration_date', '<', now())->count();

        $facilityNurses = User::where('facility_id', $facilityId)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'bcc_admin');
            })
            ->count();


        return view('pages.facility_admin.bcc_dashboard', [
            'samplesNr' => $samplesNr,
            'expiredSamples' => $expiredSamples,
            'facilityNurses' => $facilityNurses
        ]);
    }
}
