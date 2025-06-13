@extends('customer.layout')

@section('title', 'New Tender Request')

@section('content')
<div class="max-w-3xl mx-auto bg-base-100 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Request Window Tender Inspection</h2>
    <p class="text-base-content/60 mb-6">Fill out the details below to schedule your window inspection appointment</p>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.tender-requests.store') }}" id="tender-request-form">
        @csrf

        {{-- Property Information --}}
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label"><span class="label-text">Property Address <span class="text-error">*</span></span></label>
                <input type="text" name="property_address" value="{{ old('property_address') }}" placeholder="e.g., 123 Collins Street" class="input input-bordered w-full" required>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label"><span class="label-text">Suburb</span></label>
                    <input type="text" name="suburb" value="{{ old('suburb') }}" placeholder="Melbourne" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label"><span class="label-text">State</span></label>
                    <select name="state" class="select select-bordered w-full">
                        <option value="">Select</option>
                        @foreach($states as $code => $name)
                            <option value="{{ $code }}" {{ old('state') === $code ? 'selected' : '' }}>{{ $code }} - {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text">Postcode</span></label>
                    <input type="text" name="postcode" value="{{ old('postcode') }}" placeholder="3000" pattern="[0-9]{4}" maxlength="4" class="input input-bordered w-full">
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Number of Windows <span class="text-error">*</span></span></label>
                <input type="number" name="number_of_windows" value="{{ old('number_of_windows') }}" min="1" max="1000" class="input input-bordered w-full" required>
            </div>
        </div>

        <hr class="my-6">

        {{-- Appointment Scheduling --}}
        <h3 class="text-xl font-semibold mb-2">Schedule Inspection Appointment</h3>
        <p class="text-sm text-base-content/60 mb-4">Select a date and time for your 3-hour inspection appointment</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Date Selection -->
            <div>
                <label class="label"><span class="label-text">Select Date <span class="text-error">*</span></span></label>
                <select name="selected_date" id="date-select" class="select select-bordered w-full" required>
                    @foreach($availableDates as $date)
                        <option value="{{ $date['value'] }}" {{ $selectedDate === $date['value'] ? 'selected' : '' }}>
                            {{ $date['label'] }}
                            @if($date['available_slots'] === 0)
                                (No slots available)
                            @else
                                ({{ $date['available_slots'] }} slot{{ $date['available_slots'] !== 1 ? 's' : '' }} available)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Time Slot Selection -->
            <div>
                <label class="label"><span class="label-text">Select Time Slot <span class="text-error">*</span></span></label>
                <div id="time-slots-container">
                    @if(count($availableSlots) > 0)
                        @foreach($availableSlots as $slot)
                            <label class="label cursor-pointer justify-start gap-2 p-3 border border-base-300 rounded-lg hover:border-primary mb-2">
                                <input type="radio" name="appointment_datetime" value="{{ $slot['value'] }}" class="radio radio-primary" {{ old('appointment_datetime') === $slot['value'] ? 'checked' : '' }} required>
                                <span class="label-text">{{ $slot['label'] }}</span>
                            </label>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-base-content/60">
                            <i class="iconoir-calendar-minus text-2xl mb-2"></i>
                            <p>No available slots for this date</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-6">

        {{-- Additional Information --}}
        <h3 class="text-xl font-semibold mb-2">Additional Information</h3>
        
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label"><span class="label-text">Preferred Contact Method <span class="text-error">*</span></span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="label cursor-pointer justify-start gap-2 p-3 border border-base-300 rounded-lg hover:border-primary">
                        <input type="radio" name="preferred_contact_method" value="phone" class="radio radio-primary" {{ old('preferred_contact_method', 'phone') === 'phone' ? 'checked' : '' }} required>
                        <div>
                            <span class="label-text font-medium">Phone Call</span>
                            <div class="text-sm text-base-content/60">We'll call you to confirm</div>
                        </div>
                    </label>
                    <label class="label cursor-pointer justify-start gap-2 p-3 border border-base-300 rounded-lg hover:border-primary">
                        <input type="radio" name="preferred_contact_method" value="email" class="radio radio-primary" {{ old('preferred_contact_method') === 'email' ? 'checked' : '' }} required>
                        <div>
                            <span class="label-text font-medium">Email</span>
                            <div class="text-sm text-base-content/60">We'll email you to confirm</div>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Additional Notes</span></label>
                <textarea name="customer_notes" rows="3" placeholder="Any special instructions, access requirements, or additional details about your property..." class="textarea textarea-bordered w-full">{{ old('customer_notes') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="btn btn-primary flex-1">
                <i class="iconoir-check"></i>
                Submit Request
            </button>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
// Handle date selection and update available time slots
document.getElementById('date-select').addEventListener('change', function() {
    const selectedDate = this.value;
    const timeSlotsContainer = document.getElementById('time-slots-container');
    
    // Show loading state
    timeSlotsContainer.innerHTML = '<div class="text-center py-4"><span class="loading loading-spinner"></span></div>';
    
    // Fetch available slots for the selected date
    fetch(`{{ route('customer.api.available-slots') }}?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            let slotsHtml = '';
            
            if (data.slots.length > 0) {
                data.slots.forEach(slot => {
                    slotsHtml += `
                        <label class="label cursor-pointer justify-start gap-2 p-3 border border-base-300 rounded-lg hover:border-primary mb-2">
                            <input type="radio" name="appointment_datetime" value="${slot.value}" class="radio radio-primary" required>
                            <span class="label-text">${slot.label}</span>
                        </label>
                    `;
                });
            } else {
                slotsHtml = `
                    <div class="text-center py-4 text-base-content/60">
                        <i class="iconoir-calendar-minus text-2xl mb-2"></i>
                        <p>No available slots for this date</p>
                    </div>
                `;
            }
            
            timeSlotsContainer.innerHTML = slotsHtml;
        })
        .catch(error => {
            console.error('Error fetching slots:', error);
            timeSlotsContainer.innerHTML = '<div class="alert alert-error">Error loading time slots. Please refresh the page.</div>';
        });
});
</script>
@endsection