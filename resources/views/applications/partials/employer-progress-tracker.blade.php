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

<div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
    <h5 class="font-semibold text-emerald-800 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-clipboard-check"></i>
        Employer Review Panel
    </h5>

    <!-- Progress Steps -->
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
                    @elseif($isDone)
                        text-emerald-700
                    @else
                        text-gray-500 @endif">
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

        {{-- DISPUTE STATE — overrides all other progress views --}}
        @if ($application->escrow_status === 'disputed')
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-700 mb-1">Dispute Submitted</p>
                        <p class="text-xs text-red-600 leading-relaxed">
                            Your dispute has been received and is under admin review. Payment is on hold.
                            You will be notified once the admin resolves it.
                        </p>
                        @if ($application->dispute_reason)
                            <div class="mt-2 p-2 bg-white border border-red-200 rounded text-xs text-red-700">
                                <strong>Your reason:</strong> {{ $application->dispute_reason }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif ($application->escrow_status === 'refunded')
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-check-circle text-emerald-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-emerald-700 mb-1">Dispute Resolved — Refunded</p>
                        <p class="text-xs text-emerald-600">
                            Admin reviewed the dispute and approved a refund to you. This job is now closed.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($application->progress === 'pending')
            @if ($application->escrow_status === 'not_funded')
                <div class="space-y-3">
                    <div
                        class="w-full bg-amber-50 text-amber-700 px-4 py-2 rounded-lg text-sm font-medium border border-amber-200">
                        <i class="fa-solid fa-lock mr-2"></i>
                        Worker cannot start until escrow is funded.
                    </div>

                    <form action="{{ route('applications.payEscrow', $application->id) }}" method="POST"
                        onsubmit="showJobEscrowLoading({{ $application->id }})">
                        @csrf
                        <button type="submit" id="jobEscrowBtn-{{ $application->id }}"
                            class="w-full bg-[#1e3a8a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#0f2b5e]">
                            <span class="job-escrow-text">
                                <i class="fa-solid fa-lock mr-2"></i>Pay Into Escrow
                            </span>
                            <span class="job-escrow-loading hidden">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>Redirecting...
                            </span>
                        </button>
                    </form>
                </div>
            @else
                <div
                    class="w-full bg-green-50 text-green-700 px-4 py-2 rounded-lg text-sm font-medium border border-green-200">
                    <i class="fa-solid fa-shield-halved mr-2"></i>
                    Escrow funded. Waiting for worker to start.
                </div>
            @endif
        @elseif ($application->progress === 'in_progress')
            <div class="w-full bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-spinner mr-2"></i>
                Worker is currently working on this job
            </div>
        @elseif ($application->progress === 'completed')
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-paper-plane text-amber-600 mt-1"></i>
                    <div class="flex-1">
                        <h6 class="font-semibold text-amber-800 mb-1">Work Submitted</h6>
                        <p class="text-sm text-amber-700 mb-4">
                            The worker marked this job as completed. Review the work and either approve it or request a
                            revision.
                        </p>

                        <div class="mb-4 p-3 bg-white border border-amber-100 rounded-lg">
                            <p class="text-sm font-semibold text-gray-800 mb-2">Worker Delivery</p>

                            @if ($application->delivery_note)
                                <p class="text-sm text-gray-700 mb-2">{{ $application->delivery_note }}</p>
                            @endif

                            @if ($application->delivery_link)
                                <a href="{{ $application->delivery_link }}" target="_blank"
                                    class="block text-sm text-blue-600 underline mb-2">Open delivery link</a>
                            @endif

                            @if ($application->delivery_file)
                                @if ($application->delivery_screenshots)
                                    <div class="mt-4">
                                        <h6 class="font-semibold text-gray-800 mb-2">Screenshots / Proof</h6>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                            @foreach ($application->delivery_screenshots as $image)
                                                <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                        class="rounded-lg border border-gray-200 hover:opacity-90 transition">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <a href="{{ asset('storage/' . $application->delivery_file) }}" target="_blank"
                                    class="block text-sm text-blue-600 underline">View uploaded delivery file</a>
                            @endif
                        </div>

                        <div class="mb-4 p-3 bg-gray-50 border rounded-lg">
                            <p class="text-sm text-gray-700">
                                Revisions used: <strong>{{ $application->revision_count ?? 0 }} / 5</strong>
                            </p>
                        </div>

                        <!-- Inline Revision Box -->
                        <div id="revision-box-{{ $application->id }}" class="hidden mb-3">
                            <textarea id="revision-note-{{ $application->id }}" rows="3" placeholder="Type what the worker should revise..."
                                class="w-full px-3 py-2 border border-amber-300 rounded-lg text-sm focus:outline-none focus:border-amber-500"></textarea>
                            <button type="button" onclick="submitRevision({{ $application->id }})"
                                class="mt-2 w-full bg-amber-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">
                                Send Revision Request
                            </button>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" onclick="showInlineRevision({{ $application->id }})"
                                class="flex-1 bg-amber-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">
                                <i class="fa-solid fa-rotate-left mr-1"></i>Request Revision
                            </button>
                            <button type="button" onclick="showJobReleaseConfirmation({{ $application->id }})"
                                class="flex-1 bg-emerald-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-600">
                                <i class="fa-solid fa-check-double mr-1"></i>Confirm Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($application->progress === 'confirmed')
            <div class="w-full bg-emerald-100 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium mb-3">
                <i class="fa-solid fa-check-circle mr-2"></i>
                Job has been successfully completed and confirmed
            </div>

            @php
                $hasRated = \App\Models\Rating::where('application_id', $application->id)
                    ->where('reviewer_id', auth()->id())
                    ->exists();
            @endphp

            @if (!$hasRated)
                <div class="p-4 bg-white border border-gray-200 rounded-xl">
                    <h6 class="font-semibold text-gray-800 mb-3">Rate {{ $application->applicant->first_name }}</h6>
                    <div class="flex gap-1 mb-3" id="employer-rating-stars-{{ $application->id }}">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button"
                                onclick="setEmployerRating({{ $application->id }}, {{ $i }})"
                                class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">★</button>
                        @endfor
                    </div>
                    <textarea id="employer-review-{{ $application->id }}"
                        placeholder="Share your experience working with this person (optional)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3" rows="3" maxlength="500"></textarea>
                    <button type="button" onclick="submitEmployerRating({{ $application->id }})"
                        class="w-full px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">
                        <i class="fa-solid fa-star mr-2"></i>Submit Rating
                    </button>
                </div>
            @else
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <p class="text-sm text-emerald-800">
                        <i class="fa-solid fa-check-circle mr-2"></i>You have already rated this worker
                    </p>
                </div>
            @endif
        @endif

    </div>
</div>

<div id="jobReleaseModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
                </div>

                <h3 class="text-lg font-bold text-gray-900">
                    Confirm Job Completion
                </h3>
            </div>

            <div class="space-y-3 text-sm text-gray-700">
                <p>
                    You are about to release the escrow payment to the worker.
                </p>

                <ul class="space-y-2">
                    <li>✓ The job was completed satisfactorily</li>
                    <li>✓ The delivered work matches the agreed requirements</li>
                    <li>✓ You have reviewed the work carefully</li>
                    <li>✓ You do not need a revision</li>
                    <li>✓ You do not wish to open a dispute</li>
                </ul>

                <div class="p-3 rounded-lg bg-red-50 border border-red-200">
                    <p class="text-red-700 font-medium">
                        Once payment is released, the escrow process will be completed.
                    </p>
                </div>

                <p>
                    If there is any issue with the work, cancel this and request a revision instead.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 mt-6">
                <button onclick="closeJobReleaseConfirmation()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>

                <button onclick="showJobDisputeBox()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Open Dispute Instead
                </button>

                <button id="confirmJobReleaseBtn"
                    class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Yes, Release Payment
                </button>
            </div>

            <div id="jobDisputeBox" class="hidden mt-4">
                <textarea id="jobDisputeReason" rows="3" class="w-full border border-red-300 rounded-lg p-3 text-sm"
                    placeholder="Explain why you are not satisfied with the work..."></textarea>

                <button onclick="submitJobDispute()" class="mt-2 w-full bg-red-700 text-white px-4 py-2 rounded-lg">
                    Submit Dispute
                </button>
            </div>

        </div>
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

    function showJobEscrowLoading(applicationId) {
        const button = document.getElementById(`jobEscrowBtn-${applicationId}`);

        if (!button) return true;

        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');

        button.querySelector('.job-escrow-text')?.classList.add('hidden');
        button.querySelector('.job-escrow-loading')?.classList.remove('hidden');

        return true;
    }

    let currentJobApplicationId = null;

    function showJobReleaseConfirmation(applicationId) {
        currentJobApplicationId = applicationId;

        const modal = document.getElementById('jobReleaseModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeJobReleaseConfirmation() {
        const modal = document.getElementById('jobReleaseModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    const confirmJobReleaseBtn = document.getElementById('confirmJobReleaseBtn');

    if (confirmJobReleaseBtn) {
        confirmJobReleaseBtn.addEventListener('click', function() {
            closeJobReleaseConfirmation();
            confirmComplete(currentJobApplicationId);
        });
    }

    function showJobDisputeBox() {
        const box = document.getElementById('jobDisputeBox');

        if (box) {
            box.classList.toggle('hidden');
        }
    }

    function submitJobDispute() {
        const reason = document.getElementById('jobDisputeReason')?.value || '';

        if (!reason.trim()) {
            alert('Please explain the issue before opening a dispute.');
            return;
        }

        fetch(`/applications/${currentJobApplicationId}/dispute`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Dispute opened successfully.');
                    location.reload();
                } else {
                    alert(data.message || 'Could not open dispute.');
                }
            })
            .catch(() => alert('Error opening dispute.'));
    }
</script>
