<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    // Указываем, какие поля можно заполнять (из твоего ТЗ)
    protected $fillable = [
        'clientName', 
        'phone', 
        'address', 
        'problemText', 
        'status', 
        'assignedTo'
    ];

    // Связь заявки с мастером (пользователем)
    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignedTo');
    }
}
