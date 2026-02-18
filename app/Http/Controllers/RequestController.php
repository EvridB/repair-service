<?php

namespace App\Http\Controllers;

use App\Models\Request as RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RequestController extends Controller
{
    // 1. Создание заявки (Страница клиента)
    public function store(Request $request) {
        $data = $request->validate([
            'clientName' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'problemText' => 'required',
        ]);

        RepairRequest::create($data + ['status' => 'new']);
        return back()->with('success', 'Заявка создана!');
    }

    // 2. Взять в работу (ЗАЩИТА ОТ ГОНКИ)
    public function takeToWork($id) {
        return DB::transaction(function () use ($id) {
            // lockForUpdate() блокирует строку в БД, пока транзакция не завершится
            $repair = RepairRequest::where('id', $id)->lockForUpdate()->first();

            // Проверка: если кто-то уже перевел в in_progress, пока мы "думали"
            if ($repair->status !== 'assigned') {
                return response()->json(['error' => 'Заявка уже изменена или недоступна'], 409);
            }

            $repair->update(['status' => 'in_progress']);
            return response()->json(['message' => 'Взято в работу']);
        });
    }

    // 3. Назначить мастера (Для диспетчера)
    public function assign($id, Request $request) {
        $repair = RepairRequest::findOrFail($id);
        $repair->update([
            'assignedTo' => $request->master_id,
            'status' => 'assigned'
        ]);
        return back();
    }
        // 4. Завершить работу (Для мастера)
    public function complete($id) {
        $repair = RepairRequest::findOrFail($id);
        $repair->update(['status' => 'done']);
        return back();
    }

}
