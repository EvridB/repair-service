<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Request as RepairRequest; // Используем псевдоним, чтобы не путать с системным Request
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаем Диспетчера
        User::create([
            'name' => 'Диспетчер Иван',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'dispatcher',
        ]);

        // 2. Создаем Мастеров
        User::create([
            'name' => 'Мастер Алексей',
            'email' => 'master1@test.com',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Мастер Сергей',
            'email' => 'master2@test.com',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        // 3. Генерируем 15 случайных заявок через фабрику
        RepairRequest::factory(15)->create();

        // 4. Специальная заявка для теста гонки
        RepairRequest::create([
            'clientName' => 'Тест Гонки',
            'phone' => '79990001122',
            'address' => 'ул. Тестовая, 1',
            'problemText' => 'Проверка lockForUpdate',
            'status' => 'assigned',
            'assignedTo' => 2,
        ]);
    }
}
