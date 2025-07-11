<?php

namespace App\Http\Controllers;

use App\Models\SampleRequest;
use Illuminate\Http\Request;
use App\Models\BloodSample;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $availableSamplesQuery = BloodSample::where('status', 'available');
        $availableSamplesNr = $availableSamplesQuery->count();

        $doctorsQuery = SampleRequest::where('doctor_id', $user->id);
        $yourPendingReqsNr = (clone $doctorsQuery)
            ->where('status', 'pending')
            ->count();

        $facilityQuery = SampleRequest::where('facility_id', $user->facility->id);
        $facilityRequestsNr = (clone $facilityQuery)->count();

        $yourLatestApprovedRequests = (clone $doctorsQuery)
            ->where('status', 'approved')
            ->latest()
            ->take(4)
            ->get();

        $requestsStatusStats = (clone $facilityQuery)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [ucfirst($item->status) => $item->total];
            });

        return view('pages.doctors.dashboard', [
            'availableSamplesNr' => $availableSamplesNr,
            'yourPendingReqsNr' => $yourPendingReqsNr,
            'facilityRequestsNr' => $facilityRequestsNr,
            'requestsStatusStats' => $requestsStatusStats,
            'yourLatestApprovedRequests' => $yourLatestApprovedRequests
        ]);
    }
}
