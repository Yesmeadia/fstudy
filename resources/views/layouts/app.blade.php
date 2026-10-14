<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'School Feasibility Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-toggle-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Reveal toggle button only when touching/hovering the sidebar */
        aside.app-sidebar .sidebar-collapse-toggle {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s ease;
        }

        aside.app-sidebar:hover .sidebar-collapse-toggle,
        aside.app-sidebar:focus-within .sidebar-collapse-toggle,
        aside.app-sidebar.is-touch .sidebar-collapse-toggle {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        @media (min-width: 1024px) {
            aside.sidebar-is-collapsed {
                width: 5rem !important;
                /* 80px */
            }

            aside.sidebar-is-collapsed .sidebar-label,
            aside.sidebar-is-collapsed .sidebar-heading,
            aside.sidebar-is-collapsed .sidebar-profile-info {
                display: none !important;
            }

            aside.sidebar-is-collapsed .sidebar-item {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            aside.sidebar-is-collapsed .sidebar-header-box {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased h-full text-slate-900 bg-slate-50 flex overflow-hidden" x-data="{ 
        sidebarOpen: false,
        sidebarTouched: false,
        sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        toggleCollapse() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebar_collapsed', this.sidebarCollapsed);
        },
        toggleSidebar() {
            if (window.innerWidth >= 1024) {
                this.toggleCollapse();
            } else {
                this.sidebarOpen = !this.sidebarOpen;
            }
        }
    }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside :class="[
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            sidebarCollapsed ? 'sidebar-is-collapsed' : '',
            sidebarTouched ? 'is-touch' : ''
        ]" @mouseenter="sidebarTouched = true" @mouseleave="sidebarTouched = false"
        @touchstart="sidebarTouched = !sidebarTouched"
        class="app-sidebar fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-300 flex flex-col transition-all duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-slate-800 shrink-0 select-none">

        <!-- Logo Branding & Sidebar Collapse Toggle -->
        <div class="h-20 flex items-center border-b border-slate-800 bg-slate-950/70 transition-all duration-300 px-4 relative z-20"
            :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
            <a href="{{ route('dashboard') }}" class="flex items-center overflow-hidden py-2"
                title="YES INDIA FOUNDATION">
                <!-- Large Logo when expanded -->
                <img src="{{ asset('logow.png') }}" alt="YES INDIA FOUNDATION"
                    class="h-12 sm:h-13 w-auto object-contain transition-all duration-300" x-show="!sidebarCollapsed">

                <!-- Favicon Logo when collapsed (Replaces YIF text) -->
                <img src="{{ asset('favicon.ico') }}" alt="YES INDIA FOUNDATION"
                    class="w-10 h-10 object-contain rounded-xl shadow-md transition-transform duration-200"
                    x-show="sidebarCollapsed"
                    style="display: none;">
            </a>

            <!-- Desktop Collapse/Expand Toggle Icon (Positioned on Upper Layer z-30, reveals on touch/hover) -->
            <button @click="toggleCollapse()" type="button"
                :class="sidebarCollapsed ? 'absolute inset-0 m-auto w-11 h-11 bg-slate-800/90 hover:bg-indigo-600 border border-indigo-500/40 text-white rounded-xl shadow-xl z-30' : 'relative z-20 text-slate-400 hover:text-white hover:bg-slate-800'"
                class="sidebar-toggle-btn sidebar-collapse-toggle hidden lg:flex items-center justify-center p-2 rounded-xl transition-all duration-200 cursor-pointer shadow-xs"
                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse to icons only'">
                <svg class="w-5 h-5 transition-transform duration-300"
                    :class="sidebarCollapsed ? 'rotate-180 text-white' : ''" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>

            <!-- Mobile Drawer Close Button -->
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 relative z-20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto transition-all duration-300 text-sm"
            :class="sidebarCollapsed ? 'lg:px-2 lg:py-4 lg:space-y-4 px-4 py-6 space-y-6' : 'px-4 py-6 space-y-6'">
            <!-- Main Section -->
            <div>
                <p x-show="!sidebarCollapsed"
                    class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Main</p>
                <div x-show="sidebarCollapsed" class="hidden lg:block w-8 h-[1px] bg-slate-800 mx-auto my-2"></div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" title="Dashboard"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                    </a>

                    <a href="{{ route('schools.index') }}" title="Schools Directory"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('schools.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Schools Directory</span>
                    </a>
                </nav>
            </div>

            <!-- Applications & Feasibility Workflow -->
            <div>
                <div class="flex items-center justify-between px-3 mb-2" x-show="!sidebarCollapsed">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Feasibility & Audits
                    </p>
                    <a href="{{ route('applications.create') }}"
                        class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">+ New</a>
                </div>
                <div x-show="sidebarCollapsed" class="hidden lg:block w-8 h-[1px] bg-slate-800 mx-auto my-2"></div>
                <nav class="space-y-1">
                    <a href="{{ route('applications.create') }}" title="Start New Feasibility Assessment"
                        x-show="sidebarCollapsed"
                        class="hidden lg:flex items-center justify-center p-2 rounded-lg font-medium transition text-indigo-400 hover:bg-slate-800 hover:text-white mb-1">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>

                    <a href="{{ route('applications.index') }}" title="All Inspections"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('applications.index') && !request('status') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">All Inspections</span>
                    </a>

                    <a href="{{ route('applications.index', ['status' => 'draft']) }}" title="Drafts In Progress"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request('status') === 'draft' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Drafts In Progress</span>
                    </a>

                    <a href="{{ route('applications.index', ['status' => 'submitted']) }}" title="Submitted for Review"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request('status') === 'submitted' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-blue-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Submitted for Review</span>
                    </a>

                    <a href="{{ route('applications.index', ['status' => 'returned_for_correction']) }}"
                        title="Returned for Revision"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request('status') === 'returned_for_correction' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Returned for Revision</span>
                    </a>

                    <a href="{{ route('applications.index', ['status' => 'approved']) }}" title="Approved Takeovers"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request('status') === 'approved' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Approved Takeovers</span>
                    </a>

                    <a href="{{ route('applications.index', ['status' => 'archived']) }}" title="Archived Proposals"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request('status') === 'archived' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Archived Proposals</span>
                    </a>
                </nav>
            </div>

            <!-- Reports & Analytics -->
            <div>
                <p x-show="!sidebarCollapsed"
                    class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Reports &
                    Exports</p>
                <div x-show="sidebarCollapsed" class="hidden lg:block w-8 h-[1px] bg-slate-800 mx-auto my-2"></div>
                <nav class="space-y-1">
                    <a href="{{ route('reports.export.csv') }}" title="Export CSV Data"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Export CSV Data</span>
                    </a>
                </nav>
            </div>

            <!-- Administration (Super Admin Only) -->
            @if (auth()->user()->isSuperAdmin())
                <div>
                    <p x-show="!sidebarCollapsed"
                        class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Administration
                    </p>
                    <div x-show="sidebarCollapsed" class="hidden lg:block w-8 h-[1px] bg-slate-800 mx-auto my-2"></div>
                    <nav class="space-y-1">
                        <a href="{{ route('users.index') }}" title="User Management"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                            :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                            <svg class="w-5 h-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">User Management</span>
                        </a>

                        <a href="{{ route('audit_logs.index') }}" title="Audit Trails"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('audit_logs.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                            :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                            <svg class="w-5 h-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Audit Trails</span>
                        </a>

                        <a href="{{ route('settings.index') }}" title="Settings"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                            :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                            <svg class="w-5 h-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate">Settings</span>
                        </a>

                        @php $trashedAppsCount = \App\Models\FeasibilityApplication::onlyTrashed()->count(); @endphp
                        <a href="{{ route('recycle_bin.index') }}" title="Recycle Bin (Soft Deleted Items)"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('recycle_bin.*') ? 'bg-rose-700 text-white font-semibold shadow-xs' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                            :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'">
                            <div class="relative flex items-center">
                                <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                @if ($trashedAppsCount > 0)
                                    <span x-show="sidebarCollapsed" class="absolute -top-1.5 -right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-slate-900"></span>
                                @endif
                            </div>
                            <span x-show="!sidebarCollapsed" class="truncate flex-1">Recycle Bin</span>
                            @if ($trashedAppsCount > 0)
                                <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                    {{ $trashedAppsCount }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>
            @endif
        </div>

        <!-- User Profile Footer -->
        <div class="p-3.5 border-t border-slate-800 bg-slate-950/50 transition-all duration-300"
            :class="sidebarCollapsed ? 'lg:p-2' : 'p-3.5'">
            <div class="flex items-center"
                :class="sidebarCollapsed ? 'lg:flex-col lg:gap-2.5 lg:justify-center' : 'justify-between'">
                <a href="{{ route('profile.edit') }}" title="Manage Profile: {{ auth()->user()->name }}"
                    class="flex items-center gap-3 overflow-hidden group hover:opacity-90 transition"
                    :class="sidebarCollapsed ? 'lg:justify-center' : ''">
                    <div
                        class="w-9 h-9 rounded-full overflow-hidden border border-slate-700 font-bold flex items-center justify-center shrink-0 shadow-xs text-xs bg-slate-800">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div x-show="!sidebarCollapsed" class="overflow-hidden text-left">
                        <p class="text-xs font-semibold text-white group-hover:text-indigo-300 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] text-slate-400 truncate">
                            {{ auth()->user()->roles->first()?->name ?? 'User' }} &bull; Profile
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Sign out"
                            class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-800/80 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Canvas -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar -->
        <header
            class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <!-- Mobile Drawer Trigger (Mobile Only, Hidden on Desktop) -->
                <button @click="sidebarOpen = true"
                    class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition"
                    title="Open Navigation Menu">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Topbar User Profile & Actions Dropdown -->
            <div class="flex items-center gap-4" x-data="{ userMenuOpen: false }">
                <!-- User Trigger -->
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-100 transition focus:outline-hidden">
                        <!-- User Name & Role -->
                        <div class="text-right leading-tight hidden sm:block">
                            <div class="text-xs font-bold text-slate-900 leading-tight tracking-tight">
                                {{ auth()->user()->name }}
                            </div>
                            <div
                                class="text-[10px] font-extrabold uppercase tracking-wider text-cyan-600 mt-0.5 truncate max-w-44">
                                {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'BOARD ADMIN' }}
                            </div>
                        </div>

                        <!-- Rounded Avatar -->
                        <div
                            class="w-10 h-10 rounded-2xl overflow-hidden border-2 border-white shadow-sm flex items-center justify-center font-bold text-sm shrink-0 bg-slate-100">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-tr from-amber-100 via-amber-200 to-amber-300 text-amber-900 flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Caret Arrow -->
                        <svg :class="userMenuOpen ? 'rotate-180' : ''"
                            class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Exact Dropdown Card from Screenshot -->
                    <div x-show="userMenuOpen" x-cloak @click.away="userMenuOpen = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        class="absolute right-0 mt-2 w-72 bg-slate-900 text-white rounded-2xl shadow-2xl border border-slate-800 p-4 z-50">

                        <!-- Header: Name, Email & Role Pills -->
                        <div class="text-left">
                            <h4 class="font-bold text-sm text-white leading-tight tracking-tight">
                                {{ auth()->user()->name }}
                            </h4>
                            <p class="text-xs text-slate-400 truncate mt-0.5">{{ auth()->user()->email }}</p>

                            <!-- Assigned Roles Pills -->
                            <div class="mt-2.5 flex flex-wrap gap-1.5">
                                @forelse (auth()->user()->roles as $r)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wider uppercase border border-cyan-400 text-cyan-400 bg-cyan-950/60 shadow-xs">
                                        {{ $r->name }}
                                    </span>
                                @empty
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wider uppercase border border-cyan-400 text-cyan-400 bg-cyan-950/60 shadow-xs">
                                        BOARD ADMIN
                                    </span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-slate-800 my-3"></div>

                        <!-- Menu Items -->
                        <nav class="space-y-1 text-left">
                            <!-- Start Assessment (replaces Browse Home Page) -->
                            <a href="{{ route('applications.create') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:text-white hover:bg-slate-800 transition group">
                                <svg class="w-4 h-4 text-cyan-400 group-hover:scale-110 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                                <span>Start Assessment</span>
                            </a>

                            <!-- My Profile -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:text-white hover:bg-slate-800 transition group">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-white group-hover:scale-110 transition"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>My Profile</span>
                            </a>
                        </nav>

                        <!-- Divider -->
                        <div class="border-t border-slate-800 my-2"></div>

                        <!-- Sign Out -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition text-left">
                                <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-8 pt-4 space-y-3">
            @if (session('success'))
                <x-alert type="success" :autoDismiss="true" :timeout="4500">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert type="error" :autoDismiss="true" :timeout="6000">
                    {{ session('error') }}
                </x-alert>
            @endif

            @if (session('warning'))
                <x-alert type="warning" :autoDismiss="true" :timeout="6000">
                    {{ session('warning') }}
                </x-alert>
            @endif

            @if (session('status'))
                <x-alert type="info" :autoDismiss="true" :timeout="5000">
                    {{ session('status') }}
                </x-alert>
            @endif

            @if ($errors->any())
                <x-alert type="error" title="Please review the validation errors below:" :autoDismiss="true"
                    :timeout="8000">
                    <ul class="list-disc pl-5 space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif
        </div>

        <!-- Dynamic Body Page Content -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 flex flex-col justify-between">
            <div class="flex-1">
                {{ $slot }}
            </div>

            <!-- Executive Dashboard Footer -->
            <footer class="mt-12 pt-6 pb-2 border-t border-slate-200/90 text-xs text-slate-500">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Left: Foundation Name & Copyright (No Logo) -->
                    <div class="text-center sm:text-left">
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            &copy; {{ date('Y') }} YES INDIA FOUNDATION &bull; All Rights Reserved.
                        </p>
                    </div>

                    <!-- Right: Operational Badges & Support -->
                    <div class="flex items-center gap-4 text-xs font-medium flex-wrap justify-center sm:justify-end">
                        <a href="mailto:hello@cyberduce.com"
                            class="text-slate-500 hover:text-indigo-600 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Helpdesk Support</span>
                        </a>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- Global Designed Confirmation Modal -->
    <x-confirm-modal />

    @stack('scripts')
</body>

</html>