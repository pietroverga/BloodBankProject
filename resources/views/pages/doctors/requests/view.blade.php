@php
    $goBack = route('doctor.requests.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Sample Request N°' . $request->id) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Sample Request Details Card -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sample Request Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p><strong>Sample ID:</strong> #{{ $request->bloodSample->id }}</p>
                        <p><strong>Blood Type:</strong>
                            {{ $request->bloodSample->blood_type }}{{ $request->bloodSample->rh_factor }}
                            ({{ $request->bloodSample->volume_ml }} mL)</p>
                        <p><strong>Collected/Expires On:</strong>
                            {{ \Carbon\Carbon::parse($request->bloodSample->collection_date)->format('d M Y') }} /
                            {{ \Carbon\Carbon::parse($request->bloodSample->expiration_date)->format('d M Y') }}
                        </p>
                        @if(isset($request->track_number))
                            <p><strong>Tracking Number:</strong> {{ $request->track_number }}</p>
                        @endif
                    </div>
                    <div>
                        <p><strong>Collected by:</strong> {{ $request->bloodSample->nurse->name }}</p>
                        <p><strong>Hospital:</strong> {{ $request->bloodSample->facility->name }}</p>
                        <p><strong>Notes:</strong> {{ $request->bloodSample->notes ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <a href="{{ $goBack }}"
                    class="inline-flex items-center px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                    Go Back
                </a>
                @if ($request->status === 'pending')
                    <a href="{{ route('doctor.requests.edit', ['id' => $request->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-900 focus:outline-none focus:ring focus:ring-green-600 active:bg-green-900 transition ease-in-out duration-150">
                        Edit
                    </a>
                @else
                    <span
                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-sm text-white opacity-50 cursor-not-allowed">
                        Edit
                    </span>
                @endif
            </div>
            </form>

        </div>
    </div>

</x-app-layout>