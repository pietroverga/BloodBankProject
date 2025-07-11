<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BCCAdminBloodSampleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $samples = BloodSample::with(['facility', 'nurse'])
            ->where('facility_id', $facilityId)
            ->get();
        return view('pages.facility_admin.samples')
            ->with('samples', $samples);
    }

}
