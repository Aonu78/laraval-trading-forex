<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    public const TYPE_BUY = 'buy';
    public const TYPE_SELL = 'sell';
    public const TYPE_BUY_UP = 'buy_up';
    public const TYPE_BUY_DOWN = 'buy_down';
    public const TYPE_SELL_UP = 'sell_up';
    public const TYPE_SELL_DOWN = 'sell_down';

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

    public static function allowedTradeTypes(): array
    {
        return [
            self::TYPE_BUY,
            self::TYPE_SELL,
            self::TYPE_BUY_UP,
            self::TYPE_BUY_DOWN,
            self::TYPE_SELL_UP,
            self::TYPE_SELL_DOWN,
        ];
    }

    public static function normalizeTradeType(?string $type): ?string
    {
        return match ($type) {
            self::TYPE_BUY => self::TYPE_BUY_UP,
            self::TYPE_SELL => self::TYPE_SELL_DOWN,
            default => $type,
        };
    }

    public static function tradeDirectionFromType(?string $type): ?string
    {
        $normalizedType = self::normalizeTradeType($type);

        return match ($normalizedType) {
            self::TYPE_BUY_UP, self::TYPE_SELL_UP => 'up',
            self::TYPE_BUY_DOWN, self::TYPE_SELL_DOWN => 'down',
            default => null,
        };
    }

    public static function tradeLabelFromType(?string $type): string
    {
        return match ($type) {
            self::TYPE_BUY => 'Buy',
            self::TYPE_SELL => 'Sell',
            self::TYPE_BUY_UP => 'Buy Up',
            self::TYPE_BUY_DOWN => 'Buy Down',
            self::TYPE_SELL_UP => 'Sell Up',
            self::TYPE_SELL_DOWN => 'Sell Down',
            default => ucfirst(str_replace('_', ' ', (string) $type)),
        };
    }

    public function getTradeLabelAttribute(): string
    {
        return self::tradeLabelFromType($this->trade_type);
    }

    public function getTradeDirectionAttribute(): ?string
    {
        return self::tradeDirectionFromType($this->trade_type);
    }

    public function getTradeIconClassAttribute(): string
    {
        return $this->trade_direction === 'up'
            ? 'fas fa-arrow-alt-circle-up text-success'
            : 'fas fa-arrow-alt-circle-down text-danger';
    }
}
