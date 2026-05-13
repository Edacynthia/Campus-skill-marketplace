<!-- Visual Step Indicator -->
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

<div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
    <h5 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-tasks"></i>
        Job Progress Tracker
    </h5>
    
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
                    @if($isCurrent) text-blue-700
                    @elseif($isDone) text-emerald-700
                    @else text-gray-500
                    @endif">
                    {{ $steps[$step]['label'] }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Progress Bar -->
    <div class="mb-3">
        <div class="flex justify-between text-xs text-gray-600 mb-1">
            <span>Progress: {{ $application->progressLabel() }}</span>
            <span>{{ $application->progressPercentage() }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                 style="width: {{ $application->progressPercentage() }}%"></div>
        </div>
    </div>

    <!-- Revision note -->
    @if($application->revision_note && $application->progress === 'in_progress')
    <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
        <div class="flex items-start gap-2">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>

            <div>
                <p class="text-xs font-semibold text-amber-700 mb-1">
                    Revision Requested
                </p>

                <p class="text-xs text-amber-700 leading-relaxed">
                    {{ $application->revision_note }}
                </p>
            </div>
        </div>
    </div>
@endif

    <!-- Action Buttons -->
    <div class="flex flex-col gap-2">
        @if($application->progress === 'pending')
            <button onclick="startWork({{ $application->id }})" 
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                <i class="fa-solid fa-play mr-2"></i>Start Job
            </button>
        @elseif($application->progress === 'in_progress')
            <button onclick="markComplete({{ $application->id }})" 
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                <i class="fa-solid fa-check mr-2"></i>Submit Work for Review
            </button>
        @elseif($application->progress === 'completed')
            <div class="w-full bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-clock mr-2"></i>Work submitted — waiting for employer confirmation
            </div>
      @elseif($application->progress === 'confirmed')
    <div class="w-full bg-emerald-100 text-emerald-800 px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-check-circle mr-2"></i>Job Confirmed Complete
    </div>

    @php
        $hasRated = \App\Models\Rating::where('application_id', $application->id)
            ->where('reviewer_id', auth()->id())
            ->exists();
    @endphp

    @if(!$hasRated)
        <div class="mt-3 p-3 bg-white border border-gray-200 rounded-lg">
            <h6 class="font-semibold text-gray-800 mb-2">Rate Your Experience</h6>

            <div class="space-y-2">
                <div class="flex items-center gap-2 mb-2">
                    <label class="text-sm text-gray-600">Your Rating:</label>

                    <div class="flex gap-1" id="rating-stars-{{ $application->id }}">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    onclick="setRating({{ $application->id }}, {{ $i }})"
                                    class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors">
                                ★
                            </button>
                        @endfor
                    </div>
                </div>

                <textarea id="review-text-{{ $application->id }}"
                          placeholder="Share your experience (optional)"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          rows="3"
                          maxlength="500"></textarea>

                <button onclick="submitRating({{ $application->id }})"
                        class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                    <i class="fa-solid fa-star mr-2"></i>Submit Rating
                </button>
            </div>
        </div>
    @else
        <div class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
            <p class="text-sm text-emerald-800">
                <i class="fa-solid fa-check-circle mr-2"></i>You have already rated this job
            </p>
        </div>
    @endif
@endif
    </div>
</div>

<script>
function setRating(applicationId, rating) {
    const container = document.getElementById(`rating-stars-${applicationId}`);
    if (!container) return;

    container.setAttribute('data-selected', rating);

    const stars = container.querySelectorAll('button');
    stars.forEach((star, index) => {
        star.classList.remove('text-yellow-400', 'text-gray-300');

        if (index < rating) {
            star.classList.add('text-yellow-400');
        } else {
            star.classList.add('text-gray-300');
        }
    });
}

function submitRating(applicationId) {
    const container = document.getElementById(`rating-stars-${applicationId}`);
    const rating = container ? container.getAttribute('data-selected') : null;

    if (!rating) {
        alert('Please select a rating before submitting');
        return;
    }

    const review = document.getElementById(`review-text-${applicationId}`)?.value || '';

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