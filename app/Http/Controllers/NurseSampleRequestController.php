<?php

namespace App\Http\Controllers;

use App\Models\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseSampleRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $requests = SampleRequest::with(['hospital', 'bloodSample', 'doctor'])
            ->whereHas('bloodSample', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->get();
        return view('pages.nurses.requests.index')
            ->with('requests', $requests);
    }

    public function evaluate(string $id)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        $sampleRequest = SampleRequest::with(['hospital', 'bloodSample', 'doctor'])
            ->where('id', $id)
            ->whereHas('bloodSample', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->first();

        if ($sampleRequest) {
            return view('pages.nurses.requests.evaluate')
                ->with('request', $sampleRequest);
        }

        flash()->error('Request not found or unauthorized!', [
            'position' => 'bottom-right',
            'closeButton' => true
        ]);
        return redirect()->back();
    }

    public function evaluateSubmit(Request $request, string $id)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;

        $sampleRequest = SampleRequest::with('bloodSample')
            ->where('id', $id)
            ->whereHas('bloodSample', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->first();

        if (!$sampleRequest) {
            flash()->error('Sample request not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }

        if ($request->input('action') === 'approve') {
            $request->validate([
                'track_number' => 'required|string|max:255',
            ]);

            $sampleRequest->track_number = $request->track_number;
            $sampleRequest->status = 'approved';
            $sampleRequest->save();

            $sampleRequest->bloodSample->update(['status' => 'used']);
            flash()->success('Sample request approved!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('nurse.requests.index');
        } else if ($request->input('action') === 'deny') {
            $sampleRequest->status = 'denied';
            $sampleRequest->save();
            $sampleRequest->bloodSample->update(['status' => 'available']);
            flash()->success('Sample request denied!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('nurse.requests.index');
        }
        flash()->error('Invalid message: id option', [
            'position' => 'bottom-right',
            'closeButton' => true
        ]);

        return redirect()->back();
    }
}
