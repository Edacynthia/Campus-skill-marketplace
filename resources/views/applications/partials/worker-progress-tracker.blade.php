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
        @endif
    </div>
</div>