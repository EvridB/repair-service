<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель диспетчера</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Панель диспетчера</h1>
            <a href="{{ route('requests.create') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Создать заявку</a>
        </div>

        <!-- ФИЛЬТР ПО СТАТУСУ -->
        <form action="{{ route('dispatcher.index') }}" method="GET" class="mb-6 flex gap-4 items-end bg-gray-50 p-4 rounded-md border">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase">Фильтр по статусу:</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border text-sm">
                    <option value="">Все заявки</option>
                    @foreach(['new', 'assigned', 'in_progress', 'done', 'canceled'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">Применить</button>
            <a href="{{ route('dispatcher.index') }}" class="text-gray-400 hover:text-gray-600 text-xs pb-2 underline transition">Сбросить</a>
        </form>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border-b text-xs uppercase text-gray-500">Клиент/Адрес</th>
                    <th class="p-3 border-b text-xs uppercase text-gray-500">Статус</th>
                    <th class="p-3 border-b text-xs uppercase text-gray-500">Мастер</th>
                    <th class="p-3 border-b text-xs uppercase text-gray-500">История изменений</th>
                    <th class="p-3 border-b text-xs uppercase text-gray-500 text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 align-top transition">
                    <td class="p-3 border-b">
                        <div class="font-bold text-gray-800 text-sm">{{ $order->clientName }}</div>
                        <div class="text-xs text-gray-500">{{ $order->address }}</div>
                    </td>
                    <td class="p-3 border-b">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                            {{ $order->status == 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status == 'assigned' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status == 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status == 'done' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status == 'canceled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="p-3 border-b">
                        <form action="{{ route('requests.assign', $order->id) }}" method="POST" class="flex flex-col gap-1">
                            @csrf
                            <select name="master_id" class="border rounded text-[11px] p-1 bg-white">
                                <option value="">Не назначен</option>
                                @foreach($masters as $master)
                                    <option value="{{ $master->id }}" {{ $order->assignedTo == $master->id ? 'selected' : '' }}>
                                        {{ $master->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="text-[10px] bg-gray-800 text-white py-0.5 rounded hover:bg-black transition">Назначить</button>
                        </form>
                    </td>
                    <!-- НОВАЯ КОЛОНКА С ИСТОРИЕЙ -->
                    <td class="p-3 border-b">
                        <div class="max-h-24 overflow-y-auto space-y-1 pr-2 custom-scrollbar">
                            @forelse($order->logs as $log)
                                <div class="text-[10px] text-gray-500 border-l-2 border-gray-200 pl-2">
                                    <span class="font-semibold">{{ $log->created_at->format('H:i') }}</span>: 
                                    <span class="text-gray-700">{{ $log->user->name ?? 'Система' }}</span> 
                                    изменил на <span class="font-medium text-gray-800">{{ $log->new_status }}</span>
                                </div>
                            @empty
                                <span class="text-[10px] text-gray-400 italic">Событий нет</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="p-3 border-b text-right">
                        @if($order->status != 'canceled' && $order->status != 'done')
                        <form action="{{ route('requests.cancel', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-tighter">Отменить</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</body>
</html>
