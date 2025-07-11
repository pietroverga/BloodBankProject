<?php

namespace App\Http\Controllers;

use App\Models\BloodSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseBloodSampleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $samples = BloodSample::with(['facility', 'nurse'])
            ->where('facility_id', $facilityId)
            ->get();
        return view('pages.nurses.samples.index')
            ->with('samples', $samples);
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $sample = BloodSample::with(['facility', 'nurse', 'sampleRequest'])
            ->where('facility_id', $facilityId)
            ->where('id', $id)
            ->first();
        if ($sample) {
            return view('pages.nurses.samples.view')
                ->with('sample', $sample);
        } else {
            flash()->error('Sample not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }
    }

    public function edit(string $id = null)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        if ($id == null) {
            return view('pages.nurses.samples.edit')
                ->with('sample', new BloodSample());
        }

        $sample = BloodSample::with(['sampleRequest'])
            ->where('facility_id', $facilityId)
            ->where('id', $id)
            ->whereNot('status', 'used')
            ->first();
        if ($sample) {
            return view('pages.nurses.samples.edit')
                ->with('sample', $sample);
        } else {
            flash()->error('Sample not found or unauthorized!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);
            return redirect()->back();
        }
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        $sampleId = $request->input('id');

        $validated = $request->validate([
            'blood_type' => 'required|in:A,B,AB,O',
            'rh_factor' => 'required|in:+,-',
            'volume_ml' => 'required|numeric|min:1|max:1000',
            'collection_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:collection_date',
            'status' => 'nullable|in:available,requested,used',
            'notes' => 'nullable|string|max:1000',
        ]);
        try {
            if ($sampleId) {
                $sample = BloodSample::with(['facility', 'nurse', 'sampleRequest'])
                    ->where('facility_id', $facilityId)
                    ->where('id', $sampleId)
                    ->first();

                if (!$sample) {
                    flash()->error('Sample not found or unauthorized.', [
                        'position' => 'bottom-right',
                        'closeButton' => true
                    ]);
                    return redirect()->route('nurse.samples.index');
                }
            } else {
                $sample = new BloodSample();
                $sample->facility_id = $facilityId;
                $sample->nurse_id = $user->id;
            }

            $sample->fill($validated);
            $sample->save();

            flash()->success('Blood sample saved successfully!', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('nurse.samples.index');

        } catch (\Exception $e) {
            flash()->error('Error saving blood sample.', [
                'position' => 'bottom-right',
                'closeButton' => true
            ]);

            return redirect()->route('nurse.samples.edit', ['sampleId' => $sampleId]);
        }
    }

    public function delete(string $sampleId)
    {
        $user = Auth::user();
        $facilityId = $user->facility->id;
        try {
            $sample = BloodSample::where('facility_id', $facilityId)
                ->where('id', $sampleId)
                ->whereNot('status', 'used')
                ->first();

            if (!$sample) {
                flash()->error('Sample not found or unauthorized.', [
                    'position' => 'bottom-right',
                    'closeButton' => true
                ]);
                return redirect()->route('nurse.samples.index');
            }
            $sampleRequest = $sample->sampleRequest;
            if ($sampleRequest) {
                $sampleRequest->delete();
            }
            $sample->delete();

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

        return redirect()->route('nurse.samples.index');
    }

}
