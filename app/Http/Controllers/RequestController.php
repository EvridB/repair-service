<?php

namespace App\Http\Controllers;

use App\Models\Request as RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RequestController extends Controller
{
    // 1. Отображение списка для Диспетчера (С ФИЛЬТРОМ)
    public function index(Request $request) {
        $query = RepairRequest::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();
        $masters = User::where('role', 'master')->get();

        return view('dispatcher', compact('orders', 'masters'));
    }

    // 2. Создание заявки (Страница клиента)
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

    // 3. Взять в работу (ЗАЩИТА ОТ ГОНКИ)
    public function takeToWork(Request $request, $id) {
        $masterId = $request->input('master_id', 1);

        return DB::transaction(function () use ($id, $masterId) {
            $repair = RepairRequest::where('id', $id)->lockForUpdate()->first();

            if (!$repair || $repair->status !== 'assigned') {
                return response()->json(['error' => 'Заявка недоступна'], 409);
            }

            $repair->update([
                'status' => 'in_progress',
                'assignedTo' => $masterId
            ]);
            
            return response()->json(['message' => 'Успешно взято в работу']);
        });
    }

    // 4. Назначить мастера (Для диспетчера)
    public function assign($id, Request $request) {
        $repair = RepairRequest::findOrFail($id);
        $repair->update([
            'assignedTo' => $request->master_id,
            'status' => 'assigned'
        ]);
        return back()->with('success', 'Мастер назначен');
    }

    // 5. ОТМЕНИТЬ заявку
    public function cancel($id) {
        $repair = RepairRequest::findOrFail($id);
        $repair->update(['status' => 'canceled']);
        return back()->with('success', 'Заявка отменена');
    }

    // 6. ЗАВЕРШИТЬ работу (метод done)
    public function done($id) {
        $repair = RepairRequest::findOrFail($id);
        $repair->update(['status' => 'done']);
        return back()->with('success', 'Ремонт завершен!');
    }
}
