<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель мастера</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10 text-center">
    <h1 class="text-3xl font-bold mb-4">Кабинет: {{ $master->name }}</h1>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        @foreach($requests as $req)
        <div class="border p-4 mb-4 rounded flex justify-between items-center text-left">
            <div>
                <p><strong>Адрес:</strong> {{ $req->address }}</p>
                <p><strong>Проблема:</strong> {{ $req->problemText }}</p>
                <p><strong>Статус:</strong> <span class="text-orange-600 font-bold">{{ $req->status }}</span></p>
            </div>
            @if($req->status == 'assigned')
            <form action="{{ route('requests.take', $req->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Взять в работу</button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
</body>
</html>
