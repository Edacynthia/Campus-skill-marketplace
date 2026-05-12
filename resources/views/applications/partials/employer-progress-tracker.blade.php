@php
    $steps = [
        'pending' => ['label' => 'Accepted', 'icon' => 'fa-handshake'],
        'in_progress' => ['label' => 'In Progress', 'icon' => 'fa-spinner'],
        'completed' => ['label' => 'Submitted', 'icon' => 'fa-paper-plane'],
        'confirmed' => ['label' => 'Confirmed', 'icon' => 'fa-check-double'],
    ];

    $progressOrder = ['pending', 'in_progress', 'completed', 'confirmed'];
    $currentIndex = array_search($application->progress, $progressOrder);
@endphp

<div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
    <h5 class="font-semibold text-emerald-800 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-clipboard-check"></i>
        Employer Review Panel
    </h5>

    <!-- Progress Steps -->
    <div class="grid grid-cols-4 gap-2 mb-4">
        @foreach($progressOrder as $index => $step)
            @php
                $isDone = $index < $currentIndex;
                $isCurrent = $index === $currentIndex;
            @endphp

            <div class="text-center">
                <div class="mx-auto w-9 h-9 rounded-full flex items-center justify-center
                    @if($isDone)
                        bg-emerald-500 text-white
                    @elseif($isCurrent)
                        bg-blue-500 text-white
                    @else
                        bg-gray-200 text-gray-500
                    @endif">

                    @if($isDone)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <i class="fa-solid {{ $steps[$step]['icon'] }} text-xs"></i>
                    @endif
                </div>

                <p class="text-[11px] mt-1 font-medium
                    @if($isCurrent)
                        text-blue-700
                    @elseif($isDone)
                        text-emerald-700
                    @else
                        text-gray-500
                    @endif">
                    {{ $steps[$step]['label'] }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Progress Bar -->
    <div class="mb-4">
        <div class="flex justify-between text-xs text-gray-600 mb-1">
            <span>Progress: {{ $application->progressLabel() }}</span>
            <span>{{ $application->progressPercentage() }}%</span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300"
                 style="width: {{ $application->progressPercentage() }}%">
            </div>
        </div>
    </div>

    <!-- Employer Actions -->
    <div class="flex flex-col gap-2">

        @if($application->progress === 'pending')
            <div class="w-full bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-clock mr-2"></i>
                Worker has not started yet
            </div>

        @elseif($application->progress === 'in_progress')
            <div class="w-full bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-spinner mr-2"></i>
                Worker is currently working on this job
            </div>

       @elseif($application->progress === 'completed')

    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-paper-plane text-amber-600 mt-1"></i>

            <div class="flex-1">
                <h6 class="font-semibold text-amber-800 mb-1">
                    Work Submitted
                </h6>

                <p class="text-sm text-amber-700 mb-4">
                    The worker marked this job as completed. Review the work and either approve it or request a revision.
                </p>

                <!-- Inline Revision Box -->
                <div id="revision-box-{{ $application->id }}" class="hidden mb-3">
                    <textarea id="revision-note-{{ $application->id }}"
                              rows="3"
                              placeholder="Type what the worker should revise..."
                              class="w-full px-3 py-2 border border-amber-300 rounded-lg text-sm focus:outline-none focus:border-amber-500"></textarea>

                    <button type="button"
                            onclick="submitRevision({{ $application->id }})"
                            class="mt-2 w-full bg-amber-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">
                        Send Revision Request
                    </button>
                </div>

                <div class="flex gap-2">
                    <button type="button"
                            onclick="showInlineRevision({{ $application->id }})"
                            class="flex-1 bg-amber-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">
                        <i class="fa-solid fa-rotate-left mr-1"></i>
                        Request Revision
                    </button>

                    <button type="button"
                            onclick="confirmComplete({{ $application->id }})"
                            class="flex-1 bg-emerald-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-600">
                        <i class="fa-solid fa-check-double mr-1"></i>
                        Confirm Complete
                    </button>
                </div>
            </div>
        </div>
    </div>


       @elseif($application->progress === 'confirmed')

    <div class="w-full bg-emerald-100 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium mb-3">
        <i class="fa-solid fa-check-circle mr-2"></i>
        Job has been successfully completed and confirmed
    </div>

    @php
        $hasRated = \App\Models\Rating::where('application_id', $application->id)
            ->where('reviewer_id', auth()->id())
            ->exists();
    @endphp

    @if(!$hasRated)
        <div class="p-4 bg-white border border-gray-200 rounded-xl">
            <h6 class="font-semibold text-gray-800 mb-3">
                Rate {{ $application->applicant->first_name }}
            </h6>

            <div class="flex gap-1 mb-3" id="employer-rating-stars-{{ $application->id }}">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            onclick="setEmployerRating({{ $application->id }}, {{ $i }})"
                            class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">
                        ★
                    </button>
                @endfor
            </div>

            <textarea id="employer-review-{{ $application->id }}"
                      placeholder="Share your experience working with this person (optional)"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3"
                      rows="3"
                      maxlength="500"></textarea>

            <button type="button"
                    onclick="submitEmployerRating({{ $application->id }})"
                    class="w-full px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">
                <i class="fa-solid fa-star mr-2"></i>
                Submit Rating
            </button>
        </div>
    @else
        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
            <p class="text-sm text-emerald-800">
                <i class="fa-solid fa-check-circle mr-2"></i>
                You have already rated this worker
            </p>
        </div>
    @endif

@endif
    </div>
</div>

<script>
function confirmComplete(applicationId) {
    if (!confirm('Confirm this job as completed?')) return;

    fetch(`/applications/${applicationId}/confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Job confirmed successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error confirming job');
        }
    })
    .catch(() => alert('Error confirming job'));
}


function showInlineRevision(applicationId) {
    const box = document.getElementById(`revision-box-${applicationId}`);
    if (box) {
        box.classList.toggle('hidden');
    }
}

function submitRevision(applicationId) {
    const note = document.getElementById(`revision-note-${applicationId}`).value;

    if (!note.trim()) {
        alert('Please enter revision instructions.');
        return;
    }

    fetch(`/applications/${applicationId}/revision`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            revision_note: note
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Revision requested successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error requesting revision');
        }
    })
    .catch(() => alert('Error requesting revision'));
}

function setEmployerRating(applicationId, rating) {
    const container = document.getElementById(`employer-rating-stars-${applicationId}`);
    if (!container) return;

    container.setAttribute('data-selected', rating);

    container.querySelectorAll('button').forEach((star, index) => {
        star.classList.remove('text-yellow-400', 'text-gray-300');
        star.classList.add(index < rating ? 'text-yellow-400' : 'text-gray-300');
    });
}

function submitEmployerRating(applicationId) {
    const container = document.getElementById(`employer-rating-stars-${applicationId}`);
    const rating = container ? container.getAttribute('data-selected') : null;

    if (!rating) {
        alert('Please select a rating.');
        return;
    }

    const review = document.getElementById(`employer-review-${applicationId}`)?.value || '';

    fetch(`/applications/${applicationId}/rate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rating: parseInt(rating),
            review: review
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Rating submitted successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error submitting rating');
        }
    })
    .catch(() => alert('Error submitting rating'));
}

</script>