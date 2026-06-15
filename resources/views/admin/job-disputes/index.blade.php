@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-[#1e3a8a]">Job Escrow Disputes</h1>
            <p class="text-gray-500 mt-1">Review job delivery disputes and decide whether to release or refund escrow.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">Back to Dashboard</a>
    </div>

    @if($disputes->count() > 0)
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-[#1e3a8a] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Job Title</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Employer</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Worker</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Amount</th>
                            <th class="px-4 py-3 text-left whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($disputes as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    {{ Str::limit($application->job->title ?? 'Job Deleted', 40) }}
                                </td>
                                <td class="px-4 py-3">{{ $application->job->employer->fullName() ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $application->applicant->fullName() ?? 'N/A' }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-600 whitespace-nowrap">
                                    ₦{{ number_format($application->escrow_amount, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                        <i class="fa-solid fa-gavel"></i> Disputed
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="openModal({{ json_encode([
                                        'id' => $application->id,
                                        'job_title' => $application->job->title ?? 'Job Deleted',
                                        'employer' => $application->job->employer->fullName() ?? 'N/A',
                                        'worker' => $application->applicant->fullName() ?? 'N/A',
                                        'dispute_reason' => $application->dispute_reason,
                                        'delivery_note' => $application->delivery_note,
                                        'delivery_link' => $application->delivery_link,
                                        'delivery_file' => $application->delivery_file,
                                        'escrow_amount' => $application->escrow_amount,
                                        'platform_fee' => $application->platform_fee,
                                        'worker_payout' => $application->worker_payout,
                                    ]) }})"
                                            class="px-3 py-2 bg-[#1e3a8a] hover:bg-blue-900 text-white rounded-lg text-sm transition">
                                        <i class="fa-solid fa-eye mr-1"></i> Review
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($disputes, 'links'))
            <div class="mt-6 flex justify-center">{{ $disputes->links('pagination::tailwind') }}</div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-gavel text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No disputes found</h3>
            <p class="text-gray-500">There are no job escrow disputes at this time.</p>
        </div>
    @endif

</div>

<!-- Modal -->
<div id="disputeModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-[#1e3a8a] text-white p-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-semibold" id="modalTitle">Dispute Details</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        <div class="p-6 space-y-5" id="modalContent"></div>
        <div class="sticky bottom-0 bg-gray-50 p-4 rounded-b-xl border-t border-gray-200 flex gap-3" id="modalActions"></div>
    </div>
</div>

<script>
    function openModal(data) {
        const modal = document.getElementById('disputeModal');
        const content = document.getElementById('modalContent');
        const actions = document.getElementById('modalActions');

        content.innerHTML = `
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div><span class="text-xs text-gray-500">Job</span><p class="font-semibold">${escapeHtml(data.job_title)}</p></div>
                    <div><span class="text-xs text-gray-500">Employer</span><p>${escapeHtml(data.employer)}</p></div>
                    <div><span class="text-xs text-gray-500">Worker</span><p>${escapeHtml(data.worker)}</p></div>
                    <div><span class="text-xs text-gray-500">Escrow Amount</span><p class="font-bold text-emerald-600">₦${formatNumber(data.escrow_amount)}</p></div>
                </div>
            </div>

            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400">
                <div class="flex items-center gap-2 mb-2"><i class="fa-solid fa-scale-balanced text-red-600"></i><h4 class="font-semibold">Dispute Reason</h4></div>
                <p class="text-gray-700">${escapeHtml(data.dispute_reason)}</p>
            </div>

            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2"><i class="fa-solid fa-paperclip text-blue-600"></i><h4 class="font-semibold">Worker Delivery</h4></div>
                ${data.delivery_note ? `<p class="text-gray-700 mb-2">${escapeHtml(data.delivery_note)}</p>` : ''}
                <div class="flex flex-wrap gap-3">
                    ${data.delivery_link ? `<a href="${escapeHtml(data.delivery_link)}" target="_blank" class="text-blue-600 hover:underline text-sm"><i class="fa-solid fa-link"></i> View Link</a>` : ''}
                    ${data.delivery_file ? `<a href="/storage/${escapeHtml(data.delivery_file)}" target="_blank" class="text-blue-600 hover:underline text-sm"><i class="fa-solid fa-file"></i> Download File</a>` : ''}
                    ${!data.delivery_note && !data.delivery_link && !data.delivery_file ? '<p class="text-gray-400 italic">No delivery provided</p>' : ''}
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="bg-gray-50 p-3 rounded"><p class="text-xs text-gray-500">Platform Fee</p><p class="font-bold">₦${formatNumber(data.platform_fee)}</p></div>
                <div class="bg-gray-50 p-3 rounded"><p class="text-xs text-gray-500">Worker Payout</p><p class="font-bold text-[#1e3a8a]">₦${formatNumber(data.worker_payout)}</p></div>
            </div>
        `;

        actions.innerHTML = `
            <form action="/admin/job-disputes/${data.id}/release" method="POST" class="flex-1">
                @csrf
                <button type="submit" onclick="return confirm('Release payment to worker?')" class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">
                    <i class="fa-solid fa-check mr-2"></i> Release to Worker
                </button>
            </form>
            <form action="/admin/job-disputes/${data.id}/refund" method="POST" class="flex-1">
                @csrf
                <textarea name="refund_reason" rows="2" required placeholder="Reason for refund..." class="w-full border rounded-lg p-2 text-sm mb-2"></textarea>
                <button type="submit" onclick="return confirm('Refund escrow to employer?')" class="w-full px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    <i class="fa-solid fa-undo-alt mr-2"></i> Refund Employer
                </button>
            </form>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('disputeModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function escapeHtml(str) { return str?.replace(/[&<>]/g, function(m){return {'&':'&amp;','<':'&lt;','>':'&gt;'}[m];}) || ''; }
    function formatNumber(num) { return new Intl.NumberFormat().format(num); }
</script>
@endsection