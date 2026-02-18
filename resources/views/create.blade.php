<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать заявку</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Новая заявка на ремонт</h1>
        <form action="{{ route('requests.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Ваше имя</label>
                <input type="text" name="clientName" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Телефон</label>
                <input type="text" name="phone" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Адрес</label>
                <input type="text" name="address" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Описание проблемы</label>
                <textarea name="problemText" class="w-full border p-2 rounded" required></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700">Отправить заявку</button>
        </form>
    </div>
</body>
</html>
