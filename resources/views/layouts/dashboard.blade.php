<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HESTIM - Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            'hestim-blue': '#00175f',
                            'hestim-dark': '#1e293b',
                        },
                    },
                },
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-hestim-blue { background-color: #00175f !important; }
            .text-hestim-blue { color: #00175f !important; }
            .border-hestim-blue { border-color: #00175f !important; }
            .hover\:bg-blue-700:hover { background-color: #00175f !important; }
            .bg-blue-600 { background-color: #00175f !important; }
            .bg-blue-700 { background-color: #00175f !important; }
            .bg-blue-500 { background-color: #00175f !important; }
            .bg-blue-800 { background-color: #00175f !important; }
            .text-blue-600 { color: #00175f !important; }
            .text-blue-700 { color: #00175f !important; }
            .text-blue-800 { color: #00175f !important; }
            .border-blue-500 { border-color: #00175f !important; }
            .border-blue-600 { border-color: #00175f !important; }
            .border-blue-700 { border-color: #00175f !important; }
            .focus\:ring-blue-500:focus { --tw-ring-color: #00175f !important; }
            .focus\:ring-2:focus { --tw-ring-color: #00175f !important; }
            .focus\:border-blue-500:focus { border-color: #00175f !important; }
            .from-blue-600 { --tw-gradient-from: #00175f !important; }
            .to-blue-700 { --tw-gradient-to: #00175f !important; }
            .hover\:bg-blue-700:hover { background-color: #00175f !important; }
            .hover\:bg-blue-800:hover { background-color: #00175f !important; }
            .hover\:text-blue-600:hover { color: #00175f !important; }
            .hover\:text-blue-800:hover { color: #00175f !important; }
            .hover\:text-blue-900:hover { color: #00175f !important; }
            .ring-blue-500 { --tw-ring-color: #00175f !important; }
            
            /* Animations */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }
            
            /* Smooth transitions */
            * {
                transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 150ms;
            }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            ::-webkit-scrollbar-thumb {
                background: #1e3a8a;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #1d4ed8;
            }
            
        </style>
    @endif
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar (fixe) -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-hestim-blue text-white flex flex-col shadow-lg z-30 overflow-y-auto">
            <!-- Logo -->
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('hestim.png') }}" alt="HESTIM" class="h-10 w-auto">
                    <div>
                        <h1 class="text-xl font-bold text-white">HESTIM</h1>
                        <p class="text-xs text-gray-300">ENGINEERING & BUSINESS SCHOOL</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.salles.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('admin.salles.*') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Salles</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Utilisateurs</span>
                    </a>
                    <a href="{{ route('admin.groupes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('admin.groupes.*') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Groupes</span>
                    </a>
                    <a href="{{ route('admin.statistiques') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('admin.statistiques') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Audit</span>
                    </a>

                    <a href="{{ route('admin.analyse_donnees') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ (request()->routeIs('admin.analyse_donnees') || request()->is('admin/analyse-donnees*')) ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5a2 2 0 012-2h12a2 2 0 012 2v14M7 12h10M7 8h6M7 16h4"></path>
                        </svg>
                        <span>Analyse des Données</span>
                    </a>
                    <a href="{{ route('admin.reservations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('admin.reservations.*') ? 'bg-blue-700' : '' }}">
                        <span>Reservation</span>
                    </a>
                @elseif(auth()->user()->isEnseignant())
                    <a href="{{ route('enseignant.planning') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('enseignant.planning') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Mon Emploi du Temps</span>
                    </a>
                    <a href="{{ route('enseignant.cours') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('enseignant.cours') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Mes Cours</span>
                    </a>
                    <a href="{{ route('enseignant.reservations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('enseignant.reservations.*') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Demander une Nouvelle Salle</span>
                    </a>
                @elseif(auth()->user()->isEtudiant())
                    <a href="{{ route('etudiant.emploi-du-temps') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('etudiant.emploi-du-temps') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Mon Emploi du Temps</span>
                    </a>
                    <a href="{{ route('etudiant.cours') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('etudiant.cours') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Mes Cours</span>
                    </a>
                    <a href="{{ route('etudiant.reservations') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('etudiant.reservations') ? 'bg-blue-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>Reserver Une Place pour une Cours</span>
                    </a>
                @endif
            </nav>

            <!-- Profile / Logout -->
            <div class="p-4 border-t border-blue-700">
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                        <span>Mon Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 rounded-lg hover:bg-blue-700 transition text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div id="mainContent" class="flex-1 flex flex-col" style="margin-left: 16rem !important; margin-right: 0 !important;">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-20">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-hestim-blue">@yield('page-title', 'Dashboard')</h1>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('notifications.index') }}" class="relative">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @auth
                                @if(auth()->user()->notifications()->where('lu', false)->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->notifications()->where('lu', false)->count() }}
                                    </span>
                                @endif
                            @endauth
                        </a>
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8 pb-20 bg-gray-50">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="fixed bottom-0 left-0 right-0 bg-hestim-blue text-white text-center py-4" style="margin-left: 16rem !important; margin-right: 0 !important;">
                <p class="text-sm">Copyright© 2025 HESTIM</p>
            </footer>
        </div>
    </div>
    
    <script>
        // Set HTML attributes to LTR
        document.documentElement.setAttribute('dir', 'ltr');
        document.documentElement.setAttribute('lang', 'fr');
        document.body.setAttribute('dir', 'ltr');
    </script>
</body>
</html>

