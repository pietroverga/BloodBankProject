@php
    $goBack = route('nurse.requests.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sample Request N°{{ $request->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Blood Sample Request Details Card -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Blood Sample Request Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p><strong>Sample ID:</strong> #{{ $request->bloodSample->id }}</p>
                        <p><strong>Blood Type:</strong>
                            {{ $request->bloodSample->blood_type }}{{ $request->bloodSample->rh_factor }}
                            ({{ $request->bloodSample->volume_ml }} mL)</p>
                        <p><strong>Collected/Expires On:</strong>
                            {{ \Carbon\Carbon::parse($request->bloodSample->collection_date)->format('d M Y') }} /
                            {{ \Carbon\Carbon::parse($request->bloodSample->expiration_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p><strong>Requested by Doctor:</strong> {{ $request->doctor->name }}</p>
                        <p><strong>Hospital:</strong> {{ $request->hospital->name }}</p>
                        <p><strong>Notes:</strong> {{ $request->notes ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Evaluate Form -->
            <form action="{{ route('nurse.requests.evaluate.submit', ['id' => $request->id]) }}" method="POST"
                class="bg-white shadow-md rounded-lg p-6">
                @csrf
                @method('POST')

                <div class="mb-4">
                    <label for="track_number" class="block text-sm font-medium text-gray-700">Track Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="track_number" id="track_number"
                        value="{{ old('track_number', $request->track_number ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter tracking number to approve">
                    @error('track_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4">
                    <button type="submit" name="action" value="approve"
                        class="text-sm bg-green-800 hover:bg-green-700 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        id="approveBtn" disabled>
                        Approve
                    </button>

                    <button type="submit" name="action" value="deny"
                        class="text-sm bg-red-700 hover:bg-red-600 text-white px-4 py-2 rounded">
                        Deny
                    </button>

                    <a href="{{ $goBack }}"
                        class="text-sm ml-auto inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const trackInput = document.getElementById('track_number');
        const approveBtn = document.getElementById('approveBtn');

        trackInput.addEventListener('input', () => {
            approveBtn.disabled = trackInput.value.trim() === '';
        });

        if (trackInput.value.trim() !== '') {
            approveBtn.disabled = false;
        }
    </script>
</x-app-layout>