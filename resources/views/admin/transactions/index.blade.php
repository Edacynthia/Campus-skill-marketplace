@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Escrow Transactions</h1>
            <p class="text-gray-500 mt-1">
                All released skill and job escrow payments.
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Paid By</th>
                    <th class="px-4 py-3 text-left">Released To</th>
                    <th class="px-4 py-3 text-right">Escrow Amount</th>
                    <th class="px-4 py-3 text-right">Platform Fee</th>
                    <th class="px-4 py-3 text-right">Payout</th>
                    <th class="px-4 py-3 text-left">Released Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $transaction['type'] === 'Skill'
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-purple-100 text-purple-700' }}">
                                {{ $transaction['type'] }}
                            </span>
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $transaction['title'] }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $transaction['payer'] }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $transaction['receiver'] }}
                        </td>

                        <td class="px-4 py-3 text-right">
                            ₦{{ number_format($transaction['amount'] ?? 0, 2) }}
                        </td>

                        <td class="px-4 py-3 text-right text-green-700 font-semibold">
                            ₦{{ number_format($transaction['platform_fee'] ?? 0, 2) }}
                            <span class="block text-xs text-gray-400">
                                {{ $transaction['fee_percent'] ?? 5 }}%
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right">
                            ₦{{ number_format($transaction['payout'] ?? 0, 2) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $transaction['released_at']
                                ? \Carbon\Carbon::parse($transaction['released_at'])->format('d M Y, h:i A')
                                : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                            No released escrow transactions yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
@endsection