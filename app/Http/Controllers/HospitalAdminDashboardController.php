<?php

namespace App\Http\Controllers;

use App\Models\SampleRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HospitalAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        $samplesQuery = SampleRequest::where('facility_id', $facilityId);
        $approvedRequests = (clone $samplesQuery)
            ->where('status', 'approved')
            ->count();
        $pendingRequests = (clone $samplesQuery)
            ->where('status', 'pending')
            ->count();
        $facilityDoctors = User::where('facility_id', $facilityId)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'hospital_admin');
            })
            ->count();

        return view('pages.facility_admin.hospital_dashboard', [
            'approvedRequests' => $approvedRequests,
            'pendingRequests' => $pendingRequests,
            'facilityDoctors' => $facilityDoctors
        ]);
    }
}
