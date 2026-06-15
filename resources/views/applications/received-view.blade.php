@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-4">

        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Application Details
                </h1>

                <p class="text-gray-500 mt-1">
                    View the applicant's message and application information.
                </p>
            </div>

            <a href="{{ route('applications.received') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Back
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ $application->job->title }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Applicant:
                    {{ $application->applicant->first_name }}
                    {{ $application->applicant->last_name }}
                </p>

                <div class="mt-4">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($application->status === 'pending')
                            bg-yellow-100 text-yellow-700
                        @elseif($application->status === 'accepted')
                            bg-green-100 text-green-700
                        @elseif($application->status === 'rejected')
                            bg-red-100 text-red-700
                        @else
                            bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        Cover Letter / Message
                    </h3>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $application->cover_letter }}
                        </p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Applied</p>
                        <p class="font-semibold text-gray-800">
                            {{ $application->created_at->format('F j, Y • g:i A') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Progress</p>
                        <p class="font-semibold text-gray-800">
                            {{ ucfirst(str_replace('_', ' ', $application->progress ?? 'pending')) }}
                        </p>
                    </div>

                </div>

                <div class="flex flex-wrap gap-3 pt-4 border-t">

                    <a href="{{ route('applications.received') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                        Close
                    </a>

                    @if($application->status === 'pending')
                        <button type="button"
                                onclick="acceptApplication({{ $application->id }})"
                                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">
                            Accept Application
                        </button>

                        <button type="button"
                                onclick="rejectApplication({{ $application->id }})"
                                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700">
                            Reject Application
                        </button>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

<script>
function acceptApplication(applicationId) {
    if (!confirm('Accept this application?')) return;

    fetch(`/applications/${applicationId}/accept`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Application accepted successfully!');
            window.location.href = "{{ route('applications.received') }}";
        } else {
            alert(data.message || 'Error accepting application');
        }
    })
    .catch(() => alert('Error accepting application'));
}

function rejectApplication(applicationId) {
    if (!confirm('Reject this application?')) return;

    fetch(`/applications/${applicationId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Application rejected successfully!');
            window.location.href = "{{ route('applications.received') }}";
        } else {
            alert(data.message || 'Error rejecting application');
        }
    })
    .catch(() => alert('Error rejecting application'));
}
</script>
@endsection