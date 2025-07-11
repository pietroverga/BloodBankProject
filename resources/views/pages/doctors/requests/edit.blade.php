@php
    $goBack = route('doctor.requests.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Sample Request N°' . $request->id) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Sample Request Details Card -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Blood Sample Details</h3>

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
                    </div>
                    <div>
                        <p><strong>Collected by:</strong> {{ $request->bloodSample->nurse->name }}</p>
                        <p><strong>Hospital:</strong> {{ $request->bloodSample->facility->name }}</p>
                        <p><strong>Notes:</strong> {{ $request->bloodSample->notes ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('doctor.requests.edit.submit', ['id' => $request->id]) }}" method="POST"
                class="bg-white shadow-md rounded-lg p-6">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Add any optional notes here...">{{ old('notes', $request->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4">
                    <button type="submit" name="action" value="approve"
                        class="text-sm bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded"
                        id="approveBtn">
                        Save
                    </button>

                    <a href="{{ $goBack }}"
                        class="text-sm ml-auto inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>