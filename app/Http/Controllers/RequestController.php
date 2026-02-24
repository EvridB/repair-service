<?php

namespace App\Http\Controllers;

use App\Models\Request as RepairRequest;
use App\Models\RequestLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequestController extends Controller
{
    /**
     * Панель диспетчера
     */
    public function index(Request $request)
    {
        $query = RepairRequest::with(['logs.user', 'assignedUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();
        $masters = User::where('role', 'master')->get();

        return view('dispatcher', compact('orders', 'masters'));
    }

    /**
     * Страница создания заявки
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Панель мастера (фильтруем только назначенные текущему мастеру)
     */
    public function masterIndex()
    {
        $orders = RepairRequest::with(['logs.user'])
            ->where('assignedTo', Auth::id()) // Только свои заявки
            ->whereIn('status', ['assigned', 'in_progress', 'done'])
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
            'clientName'  => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string|max:500',
            'problemText' => 'required|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $repairRequest = RepairRequest::create($validated + ['status' => 'new']);
            
            $this->logStatusChange($repairRequest->id, null, 'new');

            return redirect()->route('dispatcher.index')->with('success', 'Заявка создана');
        });
    }

    /**
     * Назначение мастера (Диспетчер)
     */
    public function assign(Request $request, $id)
    {
        $request->validate(['master_id' => 'required|exists:users,id']);

        return DB::transaction(function () use ($request, $id) {
            $repairRequest = RepairRequest::where('id', $id)->lockForUpdate()->firstOrFail();
            
            $oldStatus = $repairRequest->status;
            $repairRequest->update([
                'status' => 'assigned',
                'assignedTo' => $request->master_id
            ]);

            $this->logStatusChange($repairRequest->id, $oldStatus, 'assigned');

            return back()->with('success', 'Мастер назначен');
        });
    }

    /**
     * Взять в работу (Мастер) - ЗАЩИТА ОТ RACE CONDITION
     */
    public function takeToWork($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                // ПРОВЕРКА "ГОНКИ": lockForUpdate блокирует строку в БД
                $repairRequest = RepairRequest::where('id', $id)
                    ->lockForUpdate() 
                    ->firstOrFail();
                
                // Если статус уже не assigned, возвращаем 409
                if ($repairRequest->status !== 'assigned') {
                    return $this->errorResponse('Заявка уже взята в работу или недоступна.', Response::HTTP_CONFLICT);
                }

                // Проверка, что заявка назначена именно этому мастеру
                if ($repairRequest->assignedTo !== Auth::id()) {
                    return $this->errorResponse('Это не ваша заявка.', Response::HTTP_FORBIDDEN);
                }

                $oldStatus = $repairRequest->status;
                $repairRequest->update(['status' => 'in_progress']);

                $this->logStatusChange($repairRequest->id, $oldStatus, 'in_progress');

                return $this->successResponse('Вы взяли заявку в работу');
            });
        } catch (\Exception $e) {
            return $this->errorResponse('Ошибка сервера', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Завершить работу
     */
    public function done($id)
    {
        return DB::transaction(function () use ($id) {
            $repairRequest = RepairRequest::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($repairRequest->status !== 'in_progress') {
                return back()->with('error', 'Заявка должна быть в статусе "В работе"');
            }

            $oldStatus = $repairRequest->status;
            $repairRequest->update(['status' => 'done']);

            $this->logStatusChange($repairRequest->id, $oldStatus, 'done');

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

            $this->logStatusChange($repairRequest->id, $oldStatus, 'canceled');

            return back()->with('success', 'Заявка отменена');
        });
    }

    /**
     * Вспомогательные методы для чистоты кода
     */
    private function logStatusChange($requestId, $old, $new)
    {
        RequestLog::create([
            'repair_request_id' => $requestId,
            'user_id' => Auth::id(),
            'old_status' => $old,
            'new_status' => $new,
        ]);
    }

    private function successResponse($message)
    {
        return request()->expectsJson() 
            ? response()->json(['message' => $message], 200) 
            : back()->with('success', $message);
    }

    private function errorResponse($message, $code)
    {
        return request()->expectsJson() 
            ? response()->json(['error' => $message], $code) 
            : back()->with('error', $message);
    }
}
