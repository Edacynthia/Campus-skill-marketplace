@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1e3a8a]">User Management</h1>
            <p class="text-sm text-gray-500">View and manage all registered users.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left whitespace-nowrap">User</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Email</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">User Type</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">OTP Status</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Approval Status</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Account Status</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Registered</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">Actions</th>
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
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#1e3a8a]/10 text-[#1e3a8a] whitespace-nowrap">
                                        University
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 whitespace-nowrap">
                                        Non-University
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($user->otp_verified)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                        OTP Verified
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">
                                        OTP Not Verified
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($user->is_approved)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                        Approved
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if(isset($user->status) && $user->status === 'banned')
                                    <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                        Banned
                                    </span>
                                @elseif(isset($user->status) && $user->status === 'suspended')
                                    <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                        Suspended
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                        Active
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('profile.show', $user) }}" 
                                       class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition whitespace-nowrap">
                                        <i class="fa-solid fa-user"></i>
                                        View
                                    </a>

                                    @if(!$user->status || $user->status === 'active')
                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition whitespace-nowrap">
                                                Suspend
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition whitespace-nowrap">
                                                Ban
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition whitespace-nowrap">
                                                Activate
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" 
                                          onsubmit="return confirm('Delete this user permanently?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition whitespace-nowrap">
                                            Delete
                                        </button>
                                    </form>
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

    @if(method_exists($users, 'links'))
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection