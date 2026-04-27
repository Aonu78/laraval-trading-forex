

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="sp_site_card">
                <div class="card-header d-flex flex-wrap justify-content-between">
                    <div class="radio_button_list">
                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-6" name="currency"
                                value="BTC" data-pair="BTC" checked>
                            <label class="form-check-label" for="trad-6">
                                <?php echo e(__('BTC')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-2" name="currency"
                                value="ETH_USDT" data-pair="ETH/USDT">
                            <label class="form-check-label" for="trad-2">
                                <?php echo e(__('ETH (USDT)')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-3" name="currency"
                                value="BTS_USDC" data-pair="BTS/USDC">
                            <label class="form-check-label" for="trad-3">
                                <?php echo e(__('BTS (USDC)')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-4" name="currency"
                                value="BTS" data-pair="BTS">
                            <label class="form-check-label" for="trad-4">
                                <?php echo e(__('BTS')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-5" name="currency"
                                value="ETH_USDC" data-pair="ETH/USDC">
                            <label class="form-check-label" for="trad-5">
                                <?php echo e(__('ETH (USDC)')); ?>

                            </label>
                        </div>
                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-1" name="currency"
                                value="BTS_USDT" data-pair="BTS/USDT">
                            <label class="form-check-label" for="trad-1">
                                <?php echo e(__('BTS (USDT)')); ?>

                            </label>
                        </div>
                        
                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-7" name="currency"
                                value="BNB" data-pair="BNB">
                            <label class="form-check-label" for="trad-7">
                                <?php echo e(__('BNB')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-8" name="currency"
                                value="DOGE" data-pair="DOGE">
                            <label class="form-check-label" for="trad-8">
                                <?php echo e(__('DOGE')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-9" name="currency"
                                value="LTC" data-pair="LTC">
                            <label class="form-check-label" for="trad-9">
                                <?php echo e(__('LTC')); ?>

                            </label>
                        </div>

                        <div class="sp_site_radio">
                            <input type="radio" class="form-check-input currency" id="trad-10" name="currency"
                                value="XAUT" data-pair="XAUT">
                            <label class="form-check-label" for="trad-10">
                                XAUT
                            </label>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-sm sp_theme_btn order"><?php echo e(__('Place Order')); ?></button>
                    </div>
                </div>
                <div class="sp_card_body">
                    <div id="linechart"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <script>
            'use strict'


            function firePayment(elementId) {
                $.ajax({
                    url: "<?php echo e(route('user.tradeClose')); ?>",
                    method: "GET",
                    success: function(response) {
                        if (response) {
                            document.getElementById(elementId).innerHTML = "COMPLETE";
                            return
                        }

                        window.location.href = "<?php echo e(url()->current()); ?>"
                    }
                })
            }

            function getCountDown(elementId, seconds) {
                var times = seconds;

                var x = setInterval(function() {
                    var distance = times * 1000;

                    if (distance < 0) {
                        clearInterval(x);
                        firePayment(elementId);
                        return
                    }
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById(elementId).innerHTML = days + "d " + hours + "h " + minutes + "m " +
                        seconds + "s ";
                    times--;
                }, 1000);
            }
        </script>
        <div class="col-md-12 mt-4">
            <div class="sp_site_card">
                <div class="card-header">
                    <div class="card-header-items">
                        <h5 class="card-header-item"><?php echo e(__('Current Balance')); ?> :
                            <?php echo e(Config::formatter(auth()->user()->balance)); ?></h5>
                        <form action="" method="get" class="row justify-content-md-end g-3 card-header-item">
                            <div class="col-auto">
                                <input type="text" name="trx" class="form-control form-control-sm me-2"
                                    placeholder="transaction id">
                            </div>
                            <div class="col-auto">
                                <input type="date" class="form-control form-control-sm me-3" 
                                    name="date">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm sp_theme_btn"><?php echo e(__('Search')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Ref')); ?></th>
                                    <th><?php echo e(__('Currency Sym')); ?></th>
                                    <th><?php echo e(__('Trade Price At')); ?></th>
                                    <th><?php echo e(__('Trade Amount')); ?></th>
                                    <th><?php echo e(__('Trade Type')); ?></th>
                                    <th><?php echo e(__('Trade Close At')); ?></th>
                                    <th><?php echo e(__('Profit/Loss')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $trade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(strtoupper($trade->ref)); ?></td>
                                        <td><?php echo e($trade->currency); ?></td>
                                        <td><?php echo e(Config::formatter($trade->current_price)); ?></td>
                                        <td><?php echo e(Config::formatter($trade->trade_amount ?? 0)); ?></td>

                                        <td>
                                            <i class="<?php echo e($trade->trade_icon_class); ?>"></i>
                                            <?php echo e(__($trade->trade_label)); ?>

                                        </td>

                                        <td>
                                            <p id="count_<?php echo e($loop->iteration); ?>" class="mb-2">
                                                <?php if($trade->profit_type != null): ?>
                                                    <span class="sp_badge sp_badge_success">
                                                        <?php echo e($trade->trade_stop_at); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                            <script>
                                                <?php if($trade->profit_type == null): ?>
                                                    let stopTime_<?php echo e($loop->iteration); ?> = <?php echo e($trade->trade_stop_at->timestamp); ?>;
                                                    let currentTime_<?php echo e($loop->iteration); ?> = Math.floor(Date.now() / 1000);
                                                    let seconds_<?php echo e($loop->iteration); ?> = Math.max(0, stopTime_<?php echo e($loop->iteration); ?> - currentTime_<?php echo e($loop->iteration); ?>);
                                                    getCountDown("count_<?php echo e($loop->iteration); ?>", seconds_<?php echo e($loop->iteration); ?>);
                                                <?php endif; ?>
                                            </script>
                                        </td>

                                        <td>
                                            <?php if($trade->profit_type == '+'): ?>
                                                <span class="text-success"><?php echo e(__('+' . $trade->profit_amount)); ?></span>
                                            <?php elseif($trade->profit_type == '-'): ?>
                                                <span class="text-danger"><?php echo e(__('-' . $trade->loss_amount)); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($trade->status): ?>
                                                <span class="text-success"><i class="far fa-check-circle"></i></span>
                                            <?php else: ?>
                                                <span class="text-danger"><i class="fas fa-spinner fa-spin"></i></span>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center" colspan="100%">
                                            <?php echo e(__('No Trades Found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <?php if($trades->hasPages()): ?>
                    <div class="sp_card_footer">
                        <?php echo e($trades->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="order" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="post" class="order-modal">
                <?php echo csrf_field(); ?>
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title"><?php echo e(__('Order Confirmation')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-muted fs-12"><?php echo e(__('Trading Pair')); ?></div>
                                <div id="orderPair" class="fw-bold fs-5">BTC/USDT</div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted fs-12"><?php echo e(__('direction')); ?></div>
                                <div id="orderDirection" class="fw-bold text-success"><?php echo e(__('Buy Up')); ?></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted fs-12"><?php echo e(__('current price')); ?></div>
                            <div id="currentPrice" class="fw-bold fs-5">0.00</div>
                        </div>

                        <input type="hidden" name="trade_cur">
                        <input type="hidden" name="trade_price">
                        <input type="hidden" name="duration" id="durationInput" value="60">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-muted"><?php echo e(__('Select expiration time')); ?></div>
                                <div id="expiryLabel" class="text-white-50">60s</div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-light btn-sm btn-expiry active" data-expiry="30">30s <small class="d-block text-muted">30%</small></button>
                                <button type="button" class="btn btn-outline-light btn-sm btn-expiry" data-expiry="60">60s <small class="d-block text-muted">40%</small></button>
                                <button type="button" class="btn btn-outline-light btn-sm btn-expiry" data-expiry="90">90s <small class="d-block text-muted">50%</small></button>
                                <button type="button" class="btn btn-outline-light btn-sm btn-expiry" data-expiry="120">120s <small class="d-block text-muted">60%</small></button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted mb-2"><?php echo e(__('amount')); ?></div>
                            <div class="d-flex gap-2 flex-wrap mb-2">
                                <button type="button" class="btn btn-outline-light btn-sm btn-amount" data-amount="1010">1010</button>
                                <button type="button" class="btn btn-outline-light btn-sm btn-amount" data-amount="3020">3020</button>
                                <button type="button" class="btn btn-outline-light btn-sm btn-amount" data-amount="7100">7100</button>
                                <button type="button" class="btn btn-outline-light btn-sm btn-amount" data-amount="all"><?php echo e(__('all')); ?></button>
                            </div>
                            <input type="number" step="0.00000001" min="0.00000001" name="trade_amount" id="tradeAmountInput" class="form-control bg-secondary bg-opacity-10 border-0 text-white" placeholder="<?php echo e(__('Enter amount')); ?>">
                        </div>

                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <div><?php echo e(__('Balance')); ?>: <span id="orderBalance"><?php echo e(Config::formatter(auth()->user()->balance)); ?></span></div>
                            <div><?php echo e(__('handling fee')); ?>: 0% (<?php echo e(__('INR')); ?>)</div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="trading-buy-up" type="radio" name="type" value="buy_up" checked>
                                    <label class="form-check-label" for="trading-buy-up"><?php echo e(__('BUY UP')); ?></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="trading-buy-down" type="radio" name="type" value="buy_down">
                                    <label class="form-check-label" for="trading-buy-down"><?php echo e(__('BUY DOWN')); ?></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="trading-sell-up" type="radio" name="type" value="sell_up">
                                    <label class="form-check-label" for="trading-sell-up"><?php echo e(__('SELL UP')); ?></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="trading-sell-down" type="radio" name="type" value="sell_down">
                                    <label class="form-check-label" for="trading-sell-down"><?php echo e(__('SELL DOWN')); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0">
                        <button type="submit" class="btn btn-danger w-100"><?php echo e(__('OK')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div class="modal fade" id="tradeConfirm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3" style="background:#1a1a1a;border-radius:20px;">

            <div id="tradeTimer" class="mx-auto mb-3"
                 style="width:120px;height:120px;border-radius:50%;background:#2d5cff;
                 display:flex;align-items:center;justify-content:center;
                 font-size:28px;color:white;">
                60
            </div>

            <div class="text-start">
                <p>Trading Pair <span id="c_pair" class="float-end">BTC/USDT</span></p>
                <p>Direction <span id="c_type" class="float-end text-success">Buy Up</span></p>
                <p>Buy Price <span id="c_price" class="float-end"></span></p>
                <p>Amount <span id="c_amount" class="float-end"></span></p>
            </div>

            <button class="btn btn-primary w-100 mt-3" id="continueTrade">
                Continue to trade
            </button>

        </div>
    </div>
</div>
    <div class="spinner"></div>
    <div id="floatingTrade" style="display:none;">
        <div id="tradeCircle">60</div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        #floatingTrade {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

#tradeCircle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #6c757d;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;
}
        .sp_card_body {
    background: #1a1a1a;
}
        #linechart .apexcharts-tooltip {
            background-color: #220700 !important;
            border: 1px solid rgba(255, 255, 255, 0.15)
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

        .order-modal .modal-content {
            background: #0f1218;
            border-radius: 20px;
        }

        .order-modal .btn-expiry,
        .order-modal .btn-amount {
            min-width: 80px;
        }

        .order-modal .btn-expiry.active,
        .order-modal .btn-amount.active {
            background-color: #ff5b5f;
            border-color: #ff5b5f;
            color: #ffffff;
        }

        .order-modal .btn-expiry small,
        .order-modal .btn-amount small {
            font-size: 0.65rem;
            color: #b4b4b4;
        }

        .order-modal .form-control {
            color: black !important;
            background: azure !important;
        }
        
    </style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('external-script'); ?>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/apex.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
'use strict';

let activeTrade = <?php echo json_encode($activeTrade, 15, 512) ?>;
let tradeInterval;
let lastTradeData = {};
let remainingSeconds = 0;

$(window).on('load', function () {
    if (activeTrade && activeTrade.status == 0) {
        initActiveTrade(activeTrade);
    }
});


// =============================
// INIT ACTIVE TRADE (on reload)
// =============================
function initActiveTrade(trade) {
    let seconds = Math.floor(
        (new Date(trade.trade_stop_at).getTime() - new Date().getTime()) / 1000
    );

    if (seconds <= 0) return;

    remainingSeconds = seconds;

    lastTradeData = {
        currency: trade.currency,
        price: trade.current_price,
        amount: trade.trade_amount,
        type: trade.trade_type
    };

    showTradeConfirmModal();
    startTradeTimer();
}


// =============================
// SHOW CONFIRM MODAL
// =============================
function showTradeConfirmModal() {
    $('#c_pair').text(lastTradeData.currency + '/USDT');
    $('#c_price').text(lastTradeData.price);
    $('#c_amount').text(lastTradeData.amount);

    const tradeMeta = getTradeMeta(lastTradeData.type);
    $('#c_type').text(tradeMeta.label)
        .removeClass('text-success text-danger')
        .addClass(tradeMeta.direction === 'up' ? 'text-success' : 'text-danger');

    $('#tradeConfirm').modal('show');
}


// =============================
// FORM SUBMIT → CREATE TRADE
// =============================
$('.order-modal').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);

    $.post(form.attr('action'), form.serialize(), function(res) {

        let currency = $('input[name=trade_cur]').val();
        let price = $('input[name=trade_price]').val();
        let amount = $('#tradeAmountInput').val();
        let type = $('input[name=type]:checked').val();
        let seconds = parseInt($('#durationInput').val());

        // store
        lastTradeData = { currency, price, amount, type };
        remainingSeconds = seconds;

        showTradeConfirmModal();
        startTradeTimer();

        $('#order').modal('hide');
    });
});


// =============================
// TIMER (MAIN LOGIC)
// =============================
function startTradeTimer() {
    clearInterval(tradeInterval);

    updateTimerUI();

    tradeInterval = setInterval(function () {

        remainingSeconds--;

        updateTimerUI();

        if (remainingSeconds <= 0) {
            clearInterval(tradeInterval);
            $('#tradeConfirm').modal('hide');
            $('#floatingTrade').hide();

            location.reload();
        }

    }, 1000);
}


// =============================
// UPDATE TIMER UI
// =============================
function updateTimerUI() {
    $('#tradeTimer').text(remainingSeconds);
    $('#tradeCircle').text(remainingSeconds);
}
$('#continueTrade').on('click', function () {
    $('#tradeConfirm').modal('hide');   // close modal
    $('#floatingTrade').show();         // show circle
});

// =============================
// MODAL CLOSE → SHOW FLOAT
// =============================
$('#tradeConfirm').on('hidden.bs.modal', function () {
    if (remainingSeconds > 0) {
        $('#floatingTrade').show();
    }
});


// =============================
// FLOAT CLICK → OPEN MODAL
// =============================
$('#tradeCircle').on('click', function () {
    $('#floatingTrade').hide();
    showTradeConfirmModal();
});

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

        function selectedPairLabel() {
            return $("input[name='currency']:checked").data('pair') || currency;
        }

        function currentPrice(currency) {

            $.ajax({
                url: "<?php echo e(route('user.current-price')); ?>",
                method: "GET",
                data: {
                    currency: currency
                },
                success: function(response) {
                    $('#currentPrice').text(response + ' (' + selectedPairLabel() + ')')
                    $('input[name=trade_cur]').val(currency)
                    $('input[name=trade_price]').val(response)
                }
            });

        }

        function updateChart(data) {
            chart.updateSeries([{
                data: data
            }]);
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
                url: "<?php echo e(route('ticker')); ?>",
                method: "GET",
                data: {
                    currency: currency
                },
                success: function(response) {
                    chart.updateSeries([{
                        data: response
                    }]);

                }
            });
        }

        var options = {
            series: [{
                data: cryptoPrice
            }],
            chart: {
                type: 'candlestick',
                height: 400
            },
            title: {
                text: 'CandleStick Chart',
                align: 'left',
                style: {
                    color: '#ffffff'
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    style: {
                        colors: ['#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff']
                    }
                }
            },
            yaxis: {
                tooltip: {
                    enabled: true
                },
                labels: {
                    style: {
                        colors: ['#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff']
                    }
                }
            },
            grid: {
                show: true,
                borderColor: '#ffffff26',
                strokeDashArray: 0,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#linechart"), options);
        chart.render();


        const orderBalance = parseFloat('<?php echo e(auth()->user()->balance); ?>') || 0;

        function getTradeMeta(type) {
            const normalized = ({
                buy: 'buy_up',
                sell: 'sell_down'
            })[type] || type;

            const map = {
                buy_up: { label: '<?php echo e(__('Buy Up')); ?>', direction: 'up' },
                buy_down: { label: '<?php echo e(__('Buy Down')); ?>', direction: 'down' },
                sell_up: { label: '<?php echo e(__('Sell Up')); ?>', direction: 'up' },
                sell_down: { label: '<?php echo e(__('Sell Down')); ?>', direction: 'down' }
            };

            return map[normalized] || { label: normalized, direction: 'up' };
        }

        function updateOrderDirection() {
            const direction = $('input[name="type"]:checked').val();
            const tradeMeta = getTradeMeta(direction);
            $('#orderDirection').text(tradeMeta.label);
            $('#orderDirection').toggleClass('text-success', tradeMeta.direction === 'up');
            $('#orderDirection').toggleClass('text-danger', tradeMeta.direction === 'down');
        }

        function updateOrderExpiry(seconds) {
            const minutes = seconds / 60;
            $('#durationInput').val(minutes);
            $('#expiryLabel').text(seconds + 's');
            $('.btn-expiry').removeClass('active');
            $('.btn-expiry[data-expiry="' + seconds + '"]').addClass('active');
        }

        function updateOrderAmount(amount) {
            if (amount === 'all') {
                $('#tradeAmountInput').val(orderBalance.toFixed(8));
            } else {
                $('#tradeAmountInput').val(amount);
            }
            $('.btn-amount').removeClass('active');
        }

        function updateOrderPair() {
            $('#orderPair').text(selectedPairLabel());
        }

        $('.btn-expiry').on('click', function() {
            const expiry = $(this).data('expiry');
            updateOrderExpiry(expiry);
            $(this).addClass('active');
        });

        $('.btn-amount').on('click', function() {
            const amount = $(this).data('amount');
            updateOrderAmount(amount);
            $(this).addClass('active');
        });

        $('input[name="type"]').on('change', updateOrderDirection);

        let isKycVerified = <?php echo e(auth()->user()->is_kyc_verified == 1 ? 'true' : 'false'); ?>;

        $('.order').on('click', function() {
            if (!isKycVerified) {
                window.location.href = "<?php echo e(route('user.kyc')); ?>";
                return;
            }

            updateOrderPair();
            updateOrderDirection();
            updateOrderExpiry(60);
            $('#tradeAmountInput').val('');
            $('.btn-amount').removeClass('active');
            currentPrice($("input[name='currency']:checked").val());

            $('#order').modal('show');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make(Config::theme() . 'layout.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/light/user/trading.blade.php ENDPATH**/ ?>