<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientName',
        'phone',
        'address',
        'problemText',
        'status',
        'assignedTo',
        'version' // Поле для защиты от гонки (Optimistic Lock)
    ];

    /**
     * Связь с логами истории
     */
    public function logs(): HasMany
    {
        return $this->hasMany(RequestLog::class, 'repair_request_id')->orderBy('created_at', 'desc');
    }

    /**
     * Связь с мастером (пользователем)
     */
    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignedTo');
    }
}
