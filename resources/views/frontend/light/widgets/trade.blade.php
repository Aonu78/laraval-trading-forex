<section class="trade-section sp_pt_120 sp_pb_120 sp_separator_bg">
    <div class="trading-el">
        <img src="{{ Config::getFile('trade', $content->image_one) }}" alt="image">
    </div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7 text-center">
        <div class="sp_theme_top  wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s">
          <div class="sp_theme_top_caption"><i class="fas fa-bolt"></i> {{ Config::trans($content->section_header) }}</div>
          <h2 class="sp_theme_top_title"><?= Config::colorText(optional($content)->title, optional($content)->color_text_for_title) ?></h2>
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="sp_site_card">
                <div class="card-header d-flex flex-wrap justify-content-between">
                <div class="radio_button_list">
                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-1" name="currency"
                            value="BTC" data-pair="BTC/USDT" checked>
                        <label class="form-check-label" for="trad-1">
                            {{ __('BTC/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-2" name="currency"
                            value="ETH" data-pair="ETH/USDT">
                        <label class="form-check-label" for="trad-2">
                            {{ __('ETH/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-3" name="currency"
                            value="ETH_USDC" data-pair="ETH/USDC">
                        <label class="form-check-label" for="trad-3">
                            {{ __('ETH/USDC') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-4" name="currency"
                            value="BNB" data-pair="BNB/USDT">
                        <label class="form-check-label" for="trad-4">
                            {{ __('BNB/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-5" name="currency"
                            value="DOGE" data-pair="DOGE/USDT">
                        <label class="form-check-label" for="trad-5">
                            {{ __('DOGE/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-6" name="currency"
                            value="LTC" data-pair="LTC/USDT">
                        <label class="form-check-label" for="trad-6">
                            {{ __('LTC/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-7" name="currency"
                            value="DASH" data-pair="DASH/USDT">
                        <label class="form-check-label" for="trad-7">
                            {{ __('DASH/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-8" name="currency"
                            value="ETC" data-pair="ETC/USDT">
                        <label class="form-check-label" for="trad-8">
                            {{ __('ETC/USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-9" name="currency"
                            value="BCH" data-pair="BCH/USDT">
                        <label class="form-check-label" for="trad-9">
                            {{ __('BCH/USDT') }}
                        </label>
                    </div>
                </div>

                <div>
                    <a href="{{route('user.trade')}}" class="btn sp_theme_btn order">{{ Config::trans($content->button_text) }}</a>
                </div>
                </div>
                <div class="sp_card_body">
                    <div id="linechart"></div>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>

@push('style')
  <style>
      #linechart {
        height: 400px;
        width: 100%;
      }

      .sp_trading_section {
        padding: 120px 0;
      }

      .radio_button_list {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin: -3px -15px;
      }

      .radio_button_list .sp_site_radio {
        padding: 3px 15px;
      }
  </style>
@endpush


@push('script')
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
@endpush

@push('script')
    <script>
        'use strict'


        let currency = $("input[name='currency']:checked").val();
        let binanceSocket = null;
        let reconnectTimer = null;
        let activeBinanceSymbol = null;
        let chartRequestId = 0;
        const unavailableText = @json(__('Unavailable'));

        $('.currency').on('click', function() {
            currency = $(this).val();
            loadLiveMarket(currency);
        });

        function selectedPairLabel() {
            return $("input[name='currency']:checked").data('pair') || currency;
        }

        function binanceSymbolFromCurrency(currency) {
            let normalized = String(currency || '').trim().toUpperCase().replace(/[\/-]/g, '_');
            const aliases = {
                BTC: 'BTC_USDT',
                ETH: 'ETH_USDT',
                BNB: 'BNB_USDT',
                DOGE: 'DOGE_USDT',
                LTC: 'LTC_USDT',
                DASH: 'DASH_USDT',
                ETC: 'ETC_USDT',
                BCH: 'BCH_USDT'
            };

            normalized = aliases[normalized] || normalized;

            if (!normalized.includes('_')) {
                normalized += '_USDT';
            }

            const parts = normalized.split('_');

            if (parts.length < 2 || parts[0] === parts[1]) {
                return null;
            }

            return (parts[0] + parts[1]).replace(/[^A-Z0-9]/g, '');
        }

        function formatMarketPrice(price) {
            const value = Number(price);

            if (!Number.isFinite(value)) {
                return '';
            }

            if (value >= 1000) {
                return value.toFixed(2);
            }

            if (value >= 1) {
                return value.toFixed(4);
            }

            return value.toFixed(8);
        }

        function updateCurrentPrice(price) {
            const formattedPrice = formatMarketPrice(price);

            if (!formattedPrice) {
                $('#currentPrice').text(unavailableText + ' (' + selectedPairLabel() + ')');
                $('input[name=trade_price]').val('');
                return;
            }

            $('#currentPrice').text(formattedPrice + ' (' + selectedPairLabel() + ')');
            $('input[name=trade_cur]').val(currency);
            $('input[name=trade_price]').val(formattedPrice);
        }

        function currentPrice(currency) {

            $.ajax({
                url: "{{ route('user.current-price') }}",
                method: "GET",
                data: {
                    currency: currency
                },
                success: function(response) {
                    updateCurrentPrice(response);
                },
                error: function() {
                    updateCurrentPrice(null);
                }
            });

        }

        function formatCandleData(data) {
            return (data || []).map(function(candle) {
                if (typeof candle.time !== 'undefined') {
                    return {
                        time: candle.time,
                        open: Number(candle.open),
                        high: Number(candle.high),
                        low: Number(candle.low),
                        close: Number(candle.close)
                    };
                }

                return {
                    time: candle.x,
                    open: Number(candle.y[0]),
                    high: Number(candle.y[1]),
                    low: Number(candle.y[2]),
                    close: Number(candle.y[3])
                };
            });
        }

        function updateChart(data) {
            candleSeries.setData(formatCandleData(data));
            chart.timeScale().fitContent();
        }

        function fetchCryptocurrencyPrices(currency) {
            const requestId = ++chartRequestId;

            $.ajax({
                url: "{{ route('ticker') }}",
                method: "GET",
                data: {
                    currency: currency
                },
                success: function(response) {
                    if (requestId !== chartRequestId) {
                        return;
                    }

                    updateChart(response);
                },
                error: function() {
                    if (requestId === chartRequestId) {
                        candleSeries.setData([]);
                    }
                }
            });
        }

        function closeBinanceSocket() {
            if (reconnectTimer) {
                clearTimeout(reconnectTimer);
                reconnectTimer = null;
            }

            if (binanceSocket) {
                binanceSocket.onclose = null;
                binanceSocket.close();
                binanceSocket = null;
            }
        }

        function connectBinanceSocket(symbol) {
            if (!symbol) {
                updateCurrentPrice(null);
                return;
            }

            activeBinanceSymbol = symbol;
            const socket = new WebSocket('wss://data-stream.binance.vision:443/ws/' + symbol.toLowerCase() + '@kline_1m');
            binanceSocket = socket;

            socket.onmessage = function(event) {
                const payload = JSON.parse(event.data);
                const kline = payload.k;

                if (socket !== binanceSocket || !kline || payload.s !== activeBinanceSymbol) {
                    return;
                }

                candleSeries.update({
                    time: Math.floor(kline.t / 1000),
                    open: Number(kline.o),
                    high: Number(kline.h),
                    low: Number(kline.l),
                    close: Number(kline.c)
                });
                updateCurrentPrice(kline.c);
            };

            socket.onerror = function() {
                socket.close();
            };

            socket.onclose = function() {
                if (socket !== binanceSocket || activeBinanceSymbol !== symbol) {
                    return;
                }

                reconnectTimer = setTimeout(function() {
                    connectBinanceSocket(symbol);
                }, 3000);
            };
        }

        function loadLiveMarket(currency) {
            const symbol = binanceSymbolFromCurrency(currency);

            closeBinanceSocket();
            fetchCryptocurrencyPrices(currency);
            currentPrice(currency);
            connectBinanceSocket(symbol);
        }

        const chartContainer = document.getElementById('linechart');
        const chart = LightweightCharts.createChart(chartContainer, {
            width: chartContainer.clientWidth,
            height: 400,
            layout: {
                background: { color: '#1a1a1a' },
                textColor: '#ffffff'
            },
            grid: {
                vertLines: { color: '#ffffff26' },
                horzLines: { color: '#ffffff26' }
            },
            timeScale: {
                timeVisible: true,
                secondsVisible: false,
                borderColor: '#ffffff26'
            },
            rightPriceScale: {
                borderColor: '#ffffff26'
            }
        });
        const candleSeries = chart.addSeries
            ? chart.addSeries(LightweightCharts.CandlestickSeries, {
                upColor: '#26a69a',
                downColor: '#ef5350',
                borderVisible: false,
                wickUpColor: '#26a69a',
                wickDownColor: '#ef5350'
            })
            : chart.addCandlestickSeries({
                upColor: '#26a69a',
                downColor: '#ef5350',
                borderVisible: false,
                wickUpColor: '#26a69a',
                wickDownColor: '#ef5350'
            });

        window.addEventListener('resize', function() {
            chart.applyOptions({ width: chartContainer.clientWidth });
        });

        loadLiveMarket(currency);


        $('.order').on('click', function() {

            const modal = $('#order');

            modal.modal('show')
        })
    </script>
@endpush
