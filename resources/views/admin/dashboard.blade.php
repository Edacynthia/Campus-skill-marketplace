@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#1e3a8a] tracking-tight">Admin Dashboard</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitor platform performance and manage operations</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1.5 bg-[#1e3a8a]/10 text-[#1e3a8a] text-xs rounded-full font-medium">Super Admin</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Users Card -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#1e3a8a]/5 rounded-bl-3xl -mr-2 -mt-2"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-[#1e3a8a]/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">↑ 12%</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-400 mt-2">vs last month</p>
                </div>
            </div>

            <!-- Pending Approvals Card -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#1e3a8a]/5 rounded-bl-3xl -mr-2 -mt-2"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-[#1e3a8a]/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <a href="{{ route('admin.users.pending') }}" class="text-xs font-semibold text-[#1e3a8a] hover:text-[#1e3a8a]/80">Review →</a>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Pending Approvals</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingApprovals }}</p>
                    <p class="text-xs text-gray-400 mt-2">awaiting verification</p>
                </div>
            </div>

            <!-- Platform Revenue Card -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-3xl -mr-2 -mt-2"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Platform Revenue</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">₦{{ number_format($totalRevenue ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-2">this month: +₦{{ number_format(($totalRevenue ?? 0) * 0.12, 2) }}</p>
                </div>
            </div>

            <!-- Total Escrow Card -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#1e3a8a]/5 rounded-bl-3xl -mr-2 -mt-2"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-[#1e3a8a]/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div class="w-16 bg-gray-100 rounded-full h-1.5">
                            <div class="bg-[#1e3a8a] h-1.5 rounded-full" style="width: 68%"></div>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Total Escrow</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">₦{{ number_format($totalEscrow ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-2">68% of total volume</p>
                </div>
            </div>
        </div>

        <!-- Second Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
            <!-- Active Jobs -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#1e3a8a]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">ACTIVE JOBS</p>
                        <p class="text-2xl font-bold text-gray-800">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Payouts -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">TOTAL PAYOUTS</p>
                        <p class="text-2xl font-bold text-gray-800">₦{{ number_format($totalProviderPayouts ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Completed Escrow -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#1e3a8a]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">COMPLETED ESCROW</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalReleasedTransactions ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#1e3a8a]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">TOTAL BOOKINGS</p>
                        <p class="text-2xl font-bold text-gray-800">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div>
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Quick Access</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Frequently used admin actions</p>
                </div>
                <div class="h-px flex-1 bg-gray-200 ml-6"></div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <a href="{{ route('admin.users.pending') }}" 
                    class="flex flex-col items-center gap-3 bg-white p-5 rounded-2xl border border-gray-100 hover:border-[#1e3a8a]/30 hover:shadow-lg transition-all duration-300 group">
                    <div class="w-14 h-14 bg-[#1e3a8a]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-700">Approvals</p>
                        <p class="text-xs text-[#1e3a8a] font-semibold mt-0.5">{{ $pendingApprovals }} pending</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.users') }}" 
                    class="flex flex-col items-center gap-3 bg-white p-5 rounded-2xl border border-gray-100 hover:border-[#1e3a8a]/30 hover:shadow-lg transition-all duration-300 group">
                    <div class="w-14 h-14 bg-[#1e3a8a]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-700">All Users</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $totalUsers }} total</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.transactions') }}" 
                    class="flex flex-col items-center gap-3 bg-white p-5 rounded-2xl border border-gray-100 hover:border-emerald-200 hover:shadow-lg transition-all duration-300 group">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Transactions</p>
                </a>
                
                <a href="{{ route('admin.disputes') }}" 
                    class="flex flex-col items-center gap-3 bg-white p-5 rounded-2xl border border-gray-100 hover:border-[#1e3a8a]/30 hover:shadow-lg transition-all duration-300 group">
                    <div class="w-14 h-14 bg-[#1e3a8a]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Disputes</p>
                </a>
                
                <a href="{{ route('admin.jobDisputes') }}" 
                    class="flex flex-col items-center gap-3 bg-white p-5 rounded-2xl border border-gray-100 hover:border-[#1e3a8a]/30 hover:shadow-lg transition-all duration-300 group">
                    <div class="w-14 h-14 bg-[#1e3a8a]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Job Disputes</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection