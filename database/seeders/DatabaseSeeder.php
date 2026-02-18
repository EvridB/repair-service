<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Создаем Диспетчера
        User::create([
            'name' => 'Диспетчер Иван',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'dispatcher',
        ]);

        // Создаем Мастеров
        User::create([
            'name' => 'Мастер Петр',
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
    }
}
