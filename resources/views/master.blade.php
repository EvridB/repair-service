<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель мастера</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-600">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">🛠 Мои заявки</h1>
            <span class="text-sm font-medium text-gray-500 bg-gray-200 px-3 py-1 rounded-full">
                Мастер: {{ optional(auth()->user())->name ?? 'Сотрудник' }}
            </span>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="border border-gray-200 p-5 rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center bg-white hover:shadow-md transition gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-lg font-black text-blue-600">#{{ $order->id }}</span>
                            <span class="text-xs font-bold uppercase px-2 py-0.5 rounded tracking-wider
                                {{ $order->status == 'assigned' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                {{ $order->status == 'in_progress' ? 'bg-purple-100 text-purple-800 border border-purple-200' : '' }}
                                {{ $order->status == 'done' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        
                        <div class="text-gray-900 font-semibold mb-1">{{ $order->clientName }}</div>
                        <div class="text-sm text-gray-500 italic mb-2 text-xs">📍 {{ $order->address }}</div>
                        <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded border-l-2 border-gray-300 italic">
                            "{{ $order->problemText }}"
                        </div>

                        <!-- БЛОК ИСТОРИИ -->
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <h6 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">История изменений:</h6>
                            <div class="space-y-1">
                                @if($order->logs)
                                    @foreach($order->logs as $log)
                                        <div class="text-[11px] text-gray-500 flex items-center gap-2">
                                            <span class="font-mono text-gray-400">{{ $log->created_at->format('H:i') }}</span>
                                            <span class="w-1 h-1 rounded-full bg-blue-400"></span>
                                            <span>Статус: <span class="font-medium text-gray-700">{{ $log->new_status }}</span></span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 min-w-[160px]">
                        @if($order->status == 'assigned')
                            <form action="{{ route('requests.take', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm transition text-sm">
                                    Взять в работу
                                </button>
                            </form>
                        @endif

                        @if($order->status == 'in_progress')
                            <form action="{{ route('requests.done', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 shadow-sm transition text-sm">
                                    ✅ Завершить
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 italic">Назначенных заявок пока нет.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
