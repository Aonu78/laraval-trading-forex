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
                            value="BTC" checked>
                        <label class="form-check-label" for="trad-1">
                            {{ __('BTC') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-2" name="currency"
                            value="ETH">
                        <label class="form-check-label" for="trad-2">
                            {{ __('ETH') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-3" name="currency"
                            value="USDT">
                        <label class="form-check-label" for="trad-3">
                            {{ __('USDT') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-4" name="currency"
                            value="BNB">
                        <label class="form-check-label" for="trad-4">
                            {{ __('BNB') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-5" name="currency"
                            value="DOGE">
                        <label class="form-check-label" for="trad-5">
                            {{ __('DOGE') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-6" name="currency"
                            value="LTC">
                        <label class="form-check-label" for="trad-6">
                            {{ __('LTC') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-7" name="currency"
                            value="DASH">
                        <label class="form-check-label" for="trad-7">
                            {{ __('DASH') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-8" name="currency"
                            value="ETC">
                        <label class="form-check-label" for="trad-8">
                            {{ __('ETC') }}
                        </label>
                    </div>

                    <div class="sp_site_radio">
                        <input type="radio" class="form-check-input currency" id="trad-9" name="currency"
                            value="BCH">
                        <label class="form-check-label" for="trad-9">
                            {{ __('BCH') }}
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


        let cryptoPrice;

        let currency = $("input[name='currency']:checked").val();

        $('.currency').each(function(index) {
            $('.currency').eq(index).on('click', function() {
                currency = $(this).val();
                fetchCryptocurrencyPrices(currency);
                currentPrice(currency)
            })
        })

        function currentPrice(currency) {

            $.ajax({
                url: "{{ route('user.current-price') }}",
                method: "GET",
                data: {
                    currency: currency
                },
                success: function(response) {
                    $('#currentPrice').text('Current Price ' + response + '(' + currency + ')')
                    $('input[name=trade_cur]').val(currency)
                    $('input[name=trade_price]').val(response)
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

        setInterval(() => {
            fetchCryptocurrencyPrices(currency);
            currentPrice(currency);
        }, 5000);


        $(window).on("load", function() {
            fetchCryptocurrencyPrices(currency);
            currentPrice(currency);
        });


        function fetchCryptocurrencyPrices(currency) {
            $.ajax({
                url: "{{ route('ticker') }}",
                method: "GET",
                data: {
                    currency: currency
                },
                success: function(response) {
                    updateChart(response);

                }
            });
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


        $('.order').on('click', function() {

            const modal = $('#order');

            modal.modal('show')
        })
    </script>
@endpush
