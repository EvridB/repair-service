<body class="bg-[#F3F4F6] text-[#111827] font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        
        <header class="mb-12 text-center">
            <h1 class="text-4xl font-bold tracking-tight mb-2">Ремонтная служба</h1>
            <p class="text-gray-600">Выберите роль для входа в систему</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl">
            
            <!-- Клиент -->
            <a href="/requests/create" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-blue-500 transition-all">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">Клиент</h2>
                <p class="text-gray-500 text-sm">Создать новую заявку на ремонт или обслуживание.</p>
            </a>

            <!-- Диспетчер -->
            <a href="/dispatcher" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-orange-500 transition-all">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">Диспетчер</h2>
                <p class="text-gray-500 text-sm">Управление заявками, назначение мастеров и фильтрация.</p>
            </a>

            <!-- Мастер -->
            <a href="/master" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-green-500 transition-all">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 00-1 1v1a2 2 0 11-4 0v-1a1 1 0 00-1-1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">Мастер</h2>
                <p class="text-gray-500 text-sm">Просмотр своих задач и смена статусов выполнения.</p>
            </a>

        </div>

        <footer class="mt-12 text-gray-400 text-xs">
            Repair Service v1.0 | Laravel {{ app()->version() }}
        </footer>
    </div>
</body>
