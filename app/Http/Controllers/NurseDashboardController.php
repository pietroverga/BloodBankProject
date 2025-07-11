<?php

namespace App\Http\Controllers;

use App\Models\SampleRequest;
use Illuminate\Http\Request;
use App\Models\BloodSample;
use Illuminate\Support\Facades\Auth;

class NurseDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        $samplesQuery = BloodSample::where('facility_id', $facilityId)
            ->where('status', 'available');
        $samplesNr = (clone $samplesQuery)->count();
        $expiredSamples = (clone $samplesQuery)->where('expiration_date', '<', now())->count();
        $bloodTypeStats = (clone $samplesQuery)
            ->select('blood_type', 'rh_factor', \DB::raw('count(*) as total'))
            ->groupBy('blood_type', 'rh_factor')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->blood_type . $item->rh_factor => $item->total];
            });

        $requestsQuery = SampleRequest::where('status', 'pending')
            ->whereHas('bloodSample', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            });
        $pendingRequestsNr = (clone $requestsQuery)->count();
        $latestRequests = (clone $requestsQuery)->latest()
            ->take(4)
            ->get();

        return view('pages.nurses.dashboard', [
            'samplesNr' => $samplesNr,
            'expiredSamples' => $expiredSamples,
            'bloodTypeStats' => $bloodTypeStats,
            'pendingRequestsNr' => $pendingRequestsNr,
            'latestRequests' => $latestRequests
        ]);
    }
}
