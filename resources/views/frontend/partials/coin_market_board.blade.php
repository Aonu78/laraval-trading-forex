<div class="d-card not-hover coin-market-card js-coin-board">
    <div class="coin-market-card__header">
        <div>
            <h5 class="coin-market-card__title mb-0">{{ __('Live Market') }}</h5>
            <p class="coin-market-card__subtitle mb-0">{{ __('Popular coins with changing prices') }}</p>
        </div>
        <span class="coin-market-card__pulse"></span>
    </div>

    <div class="coin-market-list">
        @foreach (['BTC', 'ETH', 'DOGE', 'TRX', 'XRP', 'LTC'] as $symbol)
            <div class="coin-market-row" data-symbol="{{ $symbol }}">
                <div class="coin-market-row__asset">
                    <div class="coin-market-row__icon coin-market-row__icon--{{ strtolower($symbol) }}">
                        {{ substr($symbol, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="coin-market-row__pair mb-0">{{ $symbol }}<span>/USDT</span></h6>
                        <small class="coin-market-row__meta">{{ __('Loading market cap...') }}</small>
                    </div>
                </div>
                <div class="coin-market-row__price-wrap">
                    <div class="coin-market-row__price">{{ __('Loading...') }}</div>
                    <small class="coin-market-row__price-usd">$0.00</small>
                </div>
            </div>
        @endforeach
    </div>
</div>
