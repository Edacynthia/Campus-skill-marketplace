@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-500 mt-1">Manage users, approvals, listings, bookings, and platform activity.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Users</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Pending Approvals</p>
            <h2 class="text-3xl font-bold text-orange-600 mt-2">{{ $pendingApprovals }}</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Active Jobs</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">0</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500">Bookings</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">0</h2>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <a href="{{ route('admin.users.pending') }}"
           class="block bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-800">User Approvals</h3>
            <p class="text-sm text-gray-500 mt-2">Approve or reject pending non-university users.</p>
        </a>

        <a href="{{ route('admin.users') }}"
           class="block bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-800">User Management</h3>
            <p class="text-sm text-gray-500 mt-2">View and manage all registered users.</p>
        </a>

        <div class="bg-white p-6 rounded-xl shadow opacity-60">
            <h3 class="text-lg font-bold text-gray-800">Jobs Management</h3>
            <p class="text-sm text-gray-500 mt-2">Coming soon.</p>
        </div>

        <a href="{{ route('admin.disputes') }}"
        class="block bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-800">Reports & Disputes</h3>
            <p class="text-sm text-gray-500 mt-2">Review payment complaints and Reports.</p>
        </a>

        <div class="bg-white p-6 rounded-xl shadow opacity-60">
            <h3 class="text-lg font-bold text-gray-800">Skills Management</h3>
            <p class="text-sm text-gray-500 mt-2">Coming soon.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow opacity-60">
            <h3 class="text-lg font-bold text-gray-800">Bookings</h3>
            <p class="text-sm text-gray-500 mt-2">Coming soon.</p>
        </div>

    </div>

</div>
@endsection