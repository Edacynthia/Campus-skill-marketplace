@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            <p class="text-sm text-gray-500">View and manage all registered users.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">User Type</th>
                        <th class="px-4 py-3 text-left">OTP Status</th>
                        <th class="px-4 py-3 text-left">Approval Status</th>
                        <th class="px-4 py-3 text-left">Account Status</th>
                        <th class="px-4 py-3 text-left">Registered</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    ID: {{ $user->id }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $user->email }}
                            </td>

                            <td class="px-4 py-3">
                                @if($user->is_university_user ?? false)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        University
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                        Non-University
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($user->otp_verified)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                                        OTP Verified
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                        OTP Not Verified
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($user->is_approved)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Approved
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if(isset($user->status) && $user->status === 'banned')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        Banned
                                    </span>
                                @elseif(isset($user->status) && $user->status === 'suspended')
                                    <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-semibold">
                                        Suspended
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                        Active
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                           <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('profile.show', $user) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium text-sm transition">
                                <i class="fa-solid fa-user"></i>
                                View
                            </a>

                                <button disabled
                                        class="px-3 py-1 bg-gray-300 text-gray-600 rounded-lg text-xs cursor-not-allowed">
                                    Suspend
                                </button>

                                <button disabled
                                        class="px-3 py-1 bg-gray-300 text-gray-600 rounded-lg text-xs cursor-not-allowed">
                                    Reset OTP
                                </button>

                                <button disabled
                                        class="px-3 py-1 bg-gray-300 text-gray-600 rounded-lg text-xs cursor-not-allowed">
                                    Delete
                                </button>

                            </div>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection