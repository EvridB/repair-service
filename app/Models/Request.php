<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- ДОБАВЛЕНО
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory; // <--- ДОБАВЛЕНО (это разрешает работу фабрик)

    protected $fillable = [
        'clientName', 
        'phone', 
        'address', 
        'problemText', 
        'status', 
        'assignedTo'
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignedTo');
    }
}
