<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель диспетчера</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-3xl font-bold mb-8 text-center">Панель диспетчера</h1>
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="p-2">Клиент</th>
                    <th class="p-2">Проблема</th>
                    <th class="p-2">Статус</th>
                    <th class="p-2">Назначить мастера</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr class="border-b">
                    <td class="p-2">{{ $req->clientName }}</td>
                    <td class="p-2">{{ $req->problemText }}</td>
                    <td class="p-2 text-blue-600 font-bold uppercase">{{ $req->status }}</td>
                    <td class="p-2">
                        @if($req->status == 'new')
                        <form action="{{ route('requests.assign', $req->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="master_id" class="border p-1 rounded">
                                @foreach($masters as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">ОК</button>
                        </form>
                        @else
                            {{ $req->master->name ?? '-' }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
