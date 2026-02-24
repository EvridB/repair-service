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
        <h1 class="text-2xl font-bold mb-6">Панель диспетчера</h1>

        <!-- ФИЛЬТР ПО СТАТУСУ (Ошибка №2) -->
        <form action="{{ route('dispatcher.index') }}" method="GET" class="mb-6 flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Фильтр по статусу:</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    <option value="">Все заявки</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новые</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Назначены</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Завершены</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Отменены</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Применить</button>
            <a href="{{ route('dispatcher.index') }}" class="text-gray-500 underline text-sm pb-2">Сбросить</a>
        </form>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Клиент/Адрес</th>
                    <th class="p-3 border-b">Статус</th>
                    <th class="p-3 border-b">Мастер</th>
                    <th class="p-3 border-b">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border-b">{{ $order->id }}</td>
                    <td class="p-3 border-b">
                        <div class="font-bold">{{ $order->clientName }}</div>
                        <div class="text-sm text-gray-500">{{ $order->address }}</div>
                    </td>
                    <td class="p-3 border-b">
                        <span class="px-2 py-1 rounded text-xs 
                            {{ $order->status == 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status == 'assigned' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status == 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status == 'done' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status == 'canceled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="p-3 border-b">
                        <form action="{{ route('requests.assign', $order->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="master_id" class="border rounded text-sm p-1">
                                @foreach($masters as $master)
                                    <option value="{{ $master->id }}" {{ $order->assignedTo == $master->id ? 'selected' : '' }}>
                                        {{ $master->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="text-xs bg-gray-200 p-1 rounded">Ок</button>
                        </form>
                    </td>
                    <td class="p-3 border-b flex gap-2">
                        <!-- КНОПКА ОТМЕНЫ (Ошибка №2) -->
                        @if($order->status != 'canceled' && $order->status != 'done')
                        <form action="{{ route('requests.cancel', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Отменить</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
