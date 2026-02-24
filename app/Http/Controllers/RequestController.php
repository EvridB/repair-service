<?php

namespace App\Http\Controllers;

use App\Models\Request as RepairRequest;
use App\Models\RequestLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Панель диспетчера (соответствует роуту dispatcher.index)
     */
    public function index(Request $request)
    {
        $query = RepairRequest::with(['logs.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();
        $masters = User::where('role', 'master')->get();

        return view('dispatcher', compact('orders', 'masters'));
    }

    /**
     * Страница создания заявки (соответствует роуту requests.create)
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Панель мастера (соответствует роуту master.index)
     */
    public function masterIndex()
    {
        $orders = RepairRequest::with(['logs.user'])
            ->where('status', '!=', 'new')
            ->latest()
            ->get();

        return view('master', compact('orders'));
    }

    /**
     * Сохранение новой заявки
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clientName' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'problemText' => 'required',
        ]);

        return DB::transaction(function () use ($validated) {
            $repairRequest = RepairRequest::create($validated + ['status' => 'new']);
            
            RequestLog::create([
                'repair_request_id' => $repairRequest->id,
                'user_id' => Auth::id(),
                'old_status' => null,
                'new_status' => 'new',
            ]);

            return redirect()->route('dispatcher.index')->with('success', 'Заявка создана');
        });
    }

    /**
     * Назначение мастера
     */
    public function assign(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $repairRequest = RepairRequest::findOrFail($id);
            $oldStatus = $repairRequest->status;

            $repairRequest->update([
                'status' => 'assigned',
                'assignedTo' => $request->master_id
            ]);

            RequestLog::create([
                'repair_request_id' => $repairRequest->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => 'assigned',
            ]);

            return back()->with('success', 'Мастер назначен');
        });
    }

    /**
     * Взять в работу (соответствует роуту takeToWork)
     */
    public function takeToWork($id)
    {
        return DB::transaction(function () use ($id) {
            $repairRequest = RepairRequest::findOrFail($id);
            
            if ($repairRequest->status !== 'assigned') {
                return back()->with('error', 'Заявка недоступна для работы');
            }

            $oldStatus = $repairRequest->status;
            $repairRequest->update(['status' => 'in_progress']);

            RequestLog::create([
                'repair_request_id' => $repairRequest->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => 'in_progress',
            ]);

            return back()->with('success', 'Вы взяли заявку в работу');
        });
    }

    /**
     * Завершить работу
     */
    public function done($id)
    {
        return DB::transaction(function () use ($id) {
            $repairRequest = RepairRequest::findOrFail($id);
            $oldStatus = $repairRequest->status;

            $repairRequest->update(['status' => 'done']);

            RequestLog::create([
                'repair_request_id' => $repairRequest->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => 'done',
            ]);

            return back()->with('success', 'Заявка выполнена!');
        });
    }

    /**
     * Отмена заявки
     */
    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {
            $repairRequest = RepairRequest::findOrFail($id);
            $oldStatus = $repairRequest->status;

            $repairRequest->update(['status' => 'canceled']);

            RequestLog::create([
                'repair_request_id' => $repairRequest->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => 'canceled',
            ]);

            return back()->with('success', 'Заявка отменена');
        });
    }
}
