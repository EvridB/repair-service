<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestLog extends Model
{
    // Разрешаем массовое заполнение этих полей
    protected $fillable = [
        'repair_request_id',
        'user_id',
        'old_status',
        'new_status'
    ];

    // Связь: лог принадлежит одной заявке
    public function repairRequest(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class);
    }

    // Связь: лог принадлежит пользователю, который сделал изменение
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
