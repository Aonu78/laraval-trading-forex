<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    public const RESULT_MODE_DEFAULT = 'default';
    public const RESULT_MODE_FORCE_WIN = 'force_win';
    public const RESULT_MODE_FORCE_LOSS = 'force_loss';

    protected $fillable = [
        'ref',
        'user_id',
        'currency',
        'current_price',
        'trade_amount',
        'trade_type',
        'result_mode',
        'force_profit_amount',
        'duration',
        'trade_stop_at',
        'trade_opens_at',
    ];

    protected $casts = [
        'trade_stop_at' => 'datetime',
        'force_profit_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
