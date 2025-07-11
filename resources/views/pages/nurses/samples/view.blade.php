@php
    $goBack = route('nurse.samples.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Blood Sample #{{ $sample->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Blood Sample Details -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Blood Sample Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p><strong>Sample ID:</strong> #{{ $sample->id }}</p>
                        <p><strong>Blood Type:</strong> {{ $sample->blood_type }}{{ $sample->rh_factor }}
                            ({{ $sample->volume_ml }} mL)</p>
                        <p><strong>Collected/Expires On:</strong>
                            {{ \Carbon\Carbon::parse($sample->collection_date)->format('d M Y') }} /
                            {{ \Carbon\Carbon::parse($sample->expiration_date)->format('d M Y') }}
                        </p>
                        <p><strong>Status:</strong>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium uppercase
                                @if($sample->status === 'available') bg-green-100 text-green-800
                                @elseif($sample->status === 'requested') bg-yellow-100 text-yellow-800
                                @elseif($sample->status === 'used') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($sample->status) }}
                            </span>
                        </p>
                        @if(isset($sample->sampleRequest->track_number))
                            <p><strong>Request</strong> N°{{ $sample->sampleRequest->id }}</p>
                        @endif
                    </div>

                    <div>
                        <p><strong>Collected By:</strong> {{ $sample->nurse->name }}</p>
                        <p><strong>Hospital:</strong> {{ $sample->facility->name }}</p>
                        <p><strong>Notes:</strong> {{ $sample->notes ?? '—' }}</p>
                        @if(isset($sample->sampleRequest->track_number))
                            <p><strong>Tracking Number:</strong> {{ $sample->sampleRequest->track_number }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between mt-4">
                <a href="{{ $goBack }}"
                    class="inline-flex items-center px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                    Go Back
                </a>
                @if ($sample->status !== 'used')
                    <a href="{{ route('nurse.samples.edit', ['id' => $sample->id]) }}"
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

        </div>
    </div>
</x-app-layout>