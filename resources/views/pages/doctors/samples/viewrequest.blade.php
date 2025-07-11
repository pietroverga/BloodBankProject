@php
    $goBack = route('doctor.samples.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View & Make a Request') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Blood Sample Details Card -->
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
                    </div>
                    <div>
                        <p><strong>Collected by:</strong> {{ $sample->nurse->name }}</p>
                        <p><strong>Hospital:</strong> {{ $sample->facility->name }}</p>
                        <p><strong>Notes:</strong> {{ $sample->notes ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Request Form -->
            <form action="{{ route('doctor.samples.request.submit', ['id' => $sample->id]) }}" method="POST"
                class="bg-white shadow-md rounded-lg p-6">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Add any optional notes here..."></textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4">
                    <button type="submit" name="action" value="approve"
                        class="text-sm bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded" id="approveBtn">
                        Order Sample
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