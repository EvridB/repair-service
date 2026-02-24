<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель мастера</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Мои заявки</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="border p-4 rounded-lg flex justify-between items-center bg-white shadow-sm">
                    <div>
                        <div class="text-lg font-bold">#{{ $order->id }} - {{ $order->device_name ?? 'Ремонт' }}</div>
                        <div class="text-sm text-gray-600">{{ $order->problemText }}</div>
                        <div class="mt-2">
                            <span class="text-xs font-semibold uppercase px-2 py-1 bg-gray-200 rounded">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <!-- ДЕЙСТВИЕ: ВЗЯТЬ В РАБОТУ -->
                        @if($order->status == 'assigned')
                            <form action="{{ route('requests.take', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                    Взять в работу
                                </button>
                            </form>
                        @endif

                        <!-- ДЕЙСТВИЕ: ЗАВЕРШИТЬ (Ошибка №3) -->
                        @if($order->status == 'in_progress')
                            <form action="{{ route('requests.done', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                    Завершить
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic text-center">Назначенных заявок пока нет.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
