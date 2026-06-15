<!-- Visual Step Indicator -->
@php
    $steps = [
        'pending' => ['label' => 'Ready To Start', 'icon' => 'fa-lock'],
        'in_progress' => ['label' => 'Work In Progress', 'icon' => 'fa-spinner'],
        'completed' => ['label' => 'Submitted For Review', 'icon' => 'fa-paper-plane'],
        'confirmed' => ['label' => 'Payment Released', 'icon' => 'fa-check-double'],
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
        @foreach ($progressOrder as $index => $step)
            @php
                $isDone = $index < $currentIndex;
                $isCurrent = $index === $currentIndex;
            @endphp

            <div class="text-center">
                <div
                    class="mx-auto w-9 h-9 rounded-full flex items-center justify-center
                    @if ($isDone) bg-emerald-500 text-white
                    @elseif($isCurrent)
                        bg-blue-500 text-white
                    @else
                        bg-gray-200 text-gray-500 @endif">

                    @if ($isDone)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <i class="fa-solid {{ $steps[$step]['icon'] }} text-xs"></i>
                    @endif
                </div>

                <p
                    class="text-[11px] mt-1 font-medium
                    @if ($isCurrent) text-blue-700
                    @elseif($isDone) text-emerald-700
                    @else text-gray-500 @endif">
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
    @if ($application->revision_note && $application->progress === 'in_progress')
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
    <!-- Action Buttons -->
    <div class="flex flex-col gap-2">

        {{-- DISPUTE STATE — check this first, it overrides progress display --}}
        @if ($application->escrow_status === 'disputed')
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-700 mb-1">
                            Dispute Opened by Employer
                        </p>
                        <p class="text-xs text-red-600 leading-relaxed">
                            The employer has raised a dispute about your delivery. An admin is reviewing the case.
                            Your payment is on hold until the admin resolves this.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($application->escrow_status === 'refunded')
            <div class="p-4 bg-gray-100 border border-gray-300 rounded-lg">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-ban text-gray-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Dispute Resolved — Refunded</p>
                        <p class="text-xs text-gray-600">
                            Admin reviewed the dispute and issued a refund to the employer. This job is now closed.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($application->progress === 'pending')
            @if ($application->escrow_status === 'funded')
                <button onclick="startWork({{ $application->id }})"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                    <i class="fa-solid fa-play mr-2"></i>Start Job
                </button>
            @else
                <div
                    class="w-full bg-amber-50 text-amber-700 px-4 py-2 rounded-lg text-sm font-medium border border-amber-200">
                    <i class="fa-solid fa-lock mr-2"></i>
                    Waiting for employer to fund escrow before you can start work.
                </div>
            @endif
        @elseif ($application->progress === 'in_progress')
            <div class="p-4 bg-white border border-blue-200 rounded-lg">
                <h6 class="font-semibold text-blue-800 mb-3">Submit Job Delivery</h6>

                <textarea id="delivery-note-{{ $application->id }}" rows="3"
                    placeholder="Describe what you completed and any instructions for the employer..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3"></textarea>

                <input type="url" id="delivery-link-{{ $application->id }}" placeholder="Optional delivery link"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3">

                <label class="block text-sm font-medium text-gray-700 mb-2">Project File</label>
                <input type="file" id="delivery-file-{{ $application->id }}" class="w-full mb-4">

                <label class="block text-sm font-medium text-gray-700 mb-2">Screenshots / Proof</label>
                <input type="file" multiple accept="image/*" id="delivery-screenshots-{{ $application->id }}"
                    class="w-full mb-4">

                <button onclick="markComplete({{ $application->id }})"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Submit Work for Review
                </button>
            </div>
        @elseif ($application->progress === 'completed')
            <div class="w-full bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-clock mr-2"></i>Work submitted — waiting for employer confirmation
            </div>
        @elseif ($application->progress === 'confirmed')
            <div class="w-full bg-emerald-100 text-emerald-800 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-check-circle mr-2"></i>Job Confirmed Complete
            </div>

            @php
                $hasRated = \App\Models\Rating::where('application_id', $application->id)
                    ->where('reviewer_id', auth()->id())
                    ->exists();
            @endphp

            @if (!$hasRated)
                <div class="mt-3 p-3 bg-white border border-gray-200 rounded-lg">
                    <h6 class="font-semibold text-gray-800 mb-2">Rate Your Experience</h6>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 mb-2">
                            <label class="text-sm text-gray-600">Your Rating:</label>
                            <div class="flex gap-1" id="rating-stars-{{ $application->id }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                        onclick="setRating({{ $application->id }}, {{ $i }})"
                                        class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors">★</button>
                                @endfor
                            </div>
                        </div>
                        <textarea id="review-text-{{ $application->id }}" placeholder="Share your experience (optional)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" rows="3"
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

  function markComplete(applicationId) {
    const noteInput        = document.getElementById(`delivery-note-${applicationId}`);
    const linkInput        = document.getElementById(`delivery-link-${applicationId}`);
    const fileInput        = document.getElementById(`delivery-file-${applicationId}`);
    const screenshotsInput = document.getElementById(`delivery-screenshots-${applicationId}`);

    const note        = noteInput        ? noteInput.value.trim()  : '';
    const link        = linkInput        ? linkInput.value.trim()  : '';
    const file        = fileInput        && fileInput.files.length > 0 ? fileInput.files[0] : null;
    const screenshots = screenshotsInput ? screenshotsInput.files  : [];

    if (!note) {
        alert('Please describe the work you completed before submitting.');
        return;
    }

    const formData = new FormData();
    formData.append('delivery_note', note);
    if (link)       formData.append('delivery_link', link);
    if (file)       formData.append('delivery_file', file);
    if (screenshots && screenshots.length > 0) {
        for (let i = 0; i < screenshots.length; i++) {
            formData.append('screenshots[]', screenshots[i]);
        }
    }

    fetch(`/applications/${applicationId}/complete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const contentType = response.headers.get('content-type') || '';

        // If Laravel returned an HTML error page instead of JSON
        if (!contentType.includes('application/json')) {
            const html = await response.text();
            console.error('Server returned HTML instead of JSON:', html);
            throw new Error('Server error — check the browser console or Laravel logs for details.');
        }

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        return data;
    })
    .then(data => {
        alert(data.message || 'Work submitted successfully!');
        location.reload();
    })
    .catch(error => {
        if (error && error.errors) {
            const firstError = Object.values(error.errors)[0];
            alert(Array.isArray(firstError) ? firstError[0] : firstError);
        } else if (error && error.message) {
            alert(error.message);
        } else {
            alert('An unexpected error occurred. Please try again.');
        }
    });
}
</script>
