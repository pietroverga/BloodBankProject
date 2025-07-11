<?php

namespace App\Http\Controllers;

use App\Models\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalAdminSampleRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $requests = SampleRequest::with(['doctor', 'hospital', 'bloodSample'])
            ->where('facility_id', $user->facility->id)
            ->get();
        return view('pages.facility_admin.requests')
            ->with('requests', $requests);
    }
}
