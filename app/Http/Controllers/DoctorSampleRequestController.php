<?php

namespace App\Http\Controllers;

use App\Models\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorSampleRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $requests = SampleRequest::with(['doctor', 'hospital', 'bloodSample'])
            ->where('doctor_id', $user->id)
            ->get();
        return view('pages.doctors.requests.index')
            ->with('requests', $requests);
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $request = SampleRequest::with(['doctor', 'hospital', 'bloodSample'])
            ->where('doctor_id', $user->id)
            ->where('id', $id)
            ->first();
        if ($request) {
            return view('pages.doctors.requests.view')
                ->with('request', $request);
        } else {
            flash()->error('Request not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }
    }

    public function edit(string $id)
    {
        $user = Auth::user();
        $request = SampleRequest::with(['doctor', 'hospital', 'bloodSample'])
            ->where('doctor_id', $user->id)
            ->where('status', 'pending')
            ->where('id', $id)
            ->first();

        if ($request) {
            return view('pages.doctors.requests.edit')
                ->with('request', $request);
        } else {
            flash()->error('Request not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }
    }

    public function save(Request $request, string $requestId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);
        try {
            $request = SampleRequest::with(['doctor', 'hospital', 'bloodSample'])
                ->where('doctor_id', $user->id)
                ->where('status', 'pending')
                ->where('id', $requestId)
                ->first();

            if (!$request) {
                flash()->error('Request not found or unauthorized.', [
                    'position' => 'bottom-right',
                    'closeButton' => true
                ]);
                return redirect()->route('doctor.requests.index');
            }

            $request->fill($validated);
            $request->save();

            flash()->success('Request saved successfully!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('doctor.requests.index');

        } catch (\Exception $e) {
            flash()->error('Error saving request.', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('doctor.requests.edit', ['id' => $requestId]);
        }
    }

    public function delete(string $requestId)
    {
        $user = Auth::user();
        try {
            $request = SampleRequest::with(['doctor', 'hospital', 'bloodSample'])
                ->where('doctor_id', $user->id)
                ->where('status', 'pending')
                ->where('id', $requestId)
                ->first();

            if (!$request) {
                flash()->error('Request not found or unauthorized.', [
                    'position' => 'bottom-right',
                    'closeButton' => true
                ]);
                return redirect()->route('doctor.requests.index');
            }
            $request->bloodSample->status = 'available';
            $request->bloodSample->save();
            $request->delete();

            flash()->success('Blood sample deleted successfully!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
        } catch (\Exception $e) {
            flash()->error('Error deleting blood sample.', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
        }

        return redirect()->route('doctor.requests.index');
    }
}
