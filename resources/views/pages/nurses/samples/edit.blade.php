@php
    $goBack = route('nurse.samples.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($sample->id ? 'Edit Blood Sample #' . $sample->id : 'New Blood Sample') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('nurse.samples.save') }}">
                @csrf
                @method('POST')

                <input type="hidden" name="id" value="{{ $sample->id }}">

                <div class="bg-white shadow rounded-lg p-6 space-y-6">

                    <!-- Blood Type -->
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700">Blood Type</label>
                        <input type="text" name="blood_type" id="blood_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                            value="{{ $sample->blood_type }}" required>
                        @error('blood_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RH Factor -->
                    <div>
                        <label for="rh_factor" class="block text-sm font-medium text-gray-700">RH Factor</label>
                        <select name="rh_factor" id="rh_factor"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                            required>
                            <option value="">Select</option>
                            <option value="+" {{ $sample->rh_factor === '+' ? 'selected' : '' }}>+</option>
                            <option value="-" {{ $sample->rh_factor === '-' ? 'selected' : '' }}>−</option>
                        </select>
                        @error('rh_factor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Volume (ml) -->
                    <div>
                        <label for="volume_ml" class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                        <input type="number" name="volume_ml" id="volume_ml" step="1" min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                            value="{{ $sample->volume_ml }}" required>
                        @error('volume_ml')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Collection Date -->
                    <div>
                        <label for="collection_date" class="block text-sm font-medium text-gray-700">Collection
                            Date</label>
                        <input type="date" name="collection_date" id="collection_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                            value="{{ $sample->collection_date ? \Carbon\Carbon::parse($sample->collection_date)->format('Y-m-d') : '' }}"
                            required>
                        @error('collection_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiration Date -->
                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration
                            Date</label>
                        <input type="date" name="expiration_date" id="expiration_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                            value="{{ $sample->expiration_date ? \Carbon\Carbon::parse($sample->expiration_date)->format('Y-m-d') : '' }}"
                            required>
                        @error('expiration_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                            required {{ ($sample->status === 'used' or $sample->status === 'requested') ? 'disabled' : '' }}>
                            <option value="">Select</option>
                            <option value="available" {{ $sample->status === 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="requested" {{ $sample->status === 'requested' ? 'selected' : '' }}>Requested
                            </option>
                            <option value="used" {{ $sample->status === 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">{{ $sample->notes }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cancel + Save Button -->
                    <div class="text-right">
                        <a href="{{ $goBack }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 active:bg-gray-200 transition ease-in-out duration-150 normal-case leading-5 mr-2 h-9">
                            Cancel
                        </a>
                        <x-button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md !text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 active:bg-green-800 transition ease-in-out duration-150 normal-case leading-5 h-9">
                            Save
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>