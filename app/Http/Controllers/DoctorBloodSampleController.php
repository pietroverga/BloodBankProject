<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use App\Models\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorBloodSampleController extends Controller
{
    public function index(Request $request)
    {
        $samples = BloodSample::with(['facility', 'nurse'])
            ->where('status', 'available')
            ->get();
        return view('pages.doctors.samples.index')
            ->with('samples', $samples);
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $sample = BloodSample::with(['facility', 'nurse'])
            ->where('status', 'available')
            ->where('id', $id)
            ->first();
        if ($sample) {
            return view('pages.doctors.samples.viewrequest')
                ->with('sample', $sample);
        } else {
            flash()->error('Sample not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }
    }

    public function save(Request $request, string $sampleId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);
        try {
            $sample = BloodSample::with(['facility', 'nurse'])
                ->where('status', 'available')
                ->where('id', $sampleId)
                ->first();

            if (!$sample) {
                flash()->error('Sample not found or unauthorized.', [
                    'position' => 'bottom-right',
                    'closeButton' => true
                ]);
                return redirect()->route('doctor.samples.index');
            }
            $sampleRequest = new SampleRequest();
            $sampleRequest->doctor_id = $user->id;
            $sampleRequest->facility_id = $user->facility->id;
            $sampleRequest->blood_sample_id = $sample->id;
            $sampleRequest->status = 'pending';
            $sampleRequest->notes = $request->input('status');
            $sampleRequest->fill($validated);
            $sampleRequest->save();

            $sample->status = 'requested';
            $sample->save();

            flash()->success('Request successfully created!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('doctor.samples.index');

        } catch (\Exception $e) {
            flash()->error('Error creating request.', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('doctor.samples.view', ['id' => $sampleId]);
        }
    }
}
