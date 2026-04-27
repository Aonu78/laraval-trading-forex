<?php $__env->startSection('content'); ?>

<?php
    $plan_expired_at = now();
?>

<?php if(auth()->user()->currentplan): ?>
    <?php
        $is_subscribe = auth()->user()->currentplan()->where('is_current', 1)->first();

        if($is_subscribe){
            $plan_expired_at =  $is_subscribe->plan_expired_at;
        }
    ?>
<?php endif; ?>
<style>
.d-left-wrapper {
    background-color: rgba(15, 23, 42, 0.92);
}

</style>
    <div class="row g-sm-4 g-3">
        <div class="col-xxl-9 col-xl-8 d-custom-left">
            <div class="d-left-wrapper">
                <div class="d-left-countdown">
                    <div id="countdownTwo"></div>
                </div>
                <div class="row g-sm-4 g-3">
                    <div class="custom-xxl-6 col-xxl-6 col-xl-6 col-lg-6 col-6">
                        <div class="d-card d-icon-card">
                            <div class="d-card-icon gr-bg-1">
                                <i class="las la-credit-card"></i>
                            </div>
                            <div class="d-card-content">
                                <h4 class="d-card-balance mt-xxl-3 mt-2"><?php echo e(Config::formatter($totalbalance)); ?></h4>                                    
                                <p class="d-card-caption"><?php echo e(__('Total Balance')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="custom-xxl-6 col-xxl-6 col-xl-6 col-lg-6 col-6">
                        <div class="d-card d-icon-card">
                            <div class="d-card-icon gr-bg-2">
                                <i class="las la-hand-holding-usd"></i>
                            </div>
                            <div class="d-card-content">
                                <h4 class="d-card-amount"><?php echo e(Config::formatter($totalWithdraw)); ?></h4>
                                <p class="d-card-caption"><?php echo e(__('Total Withdraw')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="custom-xxl-6 col-xxl-6 col-xl-6 col-lg-6 col-6">
                        <div class="d-card d-icon-card">
                            <div class="d-card-icon gr-bg-3">
                                <i class="las la-chart-bar"></i>
                            </div>
                            <div class="d-card-content">
                                <h4 class="d-card-amount"><?php echo e(Config::formatter($totalPayments)); ?></h4>
                                <p class="d-card-caption"><?php echo e(__('Total Payment')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="custom-xxl-6 col-xxl-6 col-xl-6 col-lg-6 col-6">
                        <div class="d-card d-icon-card">
                            <div class="d-card-icon gr-bg-4">
                                <i class="las la-heartbeat"></i>
                            </div>
                            <div class="d-card-content">
                                <?php
                                    $healthClass = $profileHealth < 40 ? 'bg-danger' : ($profileHealth < 60 ? 'bg-warning' : ($profileHealth < 80 ? 'bg-info' : 'bg-success'));
                                ?>
                                <h4 class="d-card-amount profile-health-percent"><?php echo e($profileHealth); ?></h4>
                                <p class="d-card-caption"><?php echo e(__('Credit Score')); ?></p>
                                <small class="text-muted d-block"><?php echo e(auth()->user()->level ?? 'VIP1'); ?></small>

                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar <?php echo e($healthClass); ?>"
                                        role="progressbar"
                                        style="width: <?php echo e($profileHealth); ?>%"
                                        aria-valuenow="<?php echo e($profileHealth); ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="health-bar small">
                                    <div class="health-progress <?php echo e($healthClass); ?>" style="width: <?php echo e($profileHealth); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-xl-none d-block mt-4">
                    <div class="row g-sm-4 g-3">
                        <div class="col-xl-12 col-lg-6">
                            <div class="d-card user-card not-hover"> 
                                <div class="text-center">
                                    <h5 class="user-card-title"><?php echo e(__('Total Balance')); ?></h5>
                                    <h4 class="d-card-balance mt-xxl-3 mt-2"><?php echo e(Config::formatter($totalbalance)); ?></h4>
                                    <p class="mb-0 mt-2"><?php echo e(__('Freeze Balance')); ?>: <?php echo e(Config::formatter(auth()->user()->freeze_balance)); ?></p>
                                    <?php if(auth()->user()->is_account_freeze): ?>
                                        <span class="badge badge-danger mt-2"><?php echo e(__('Account Freeze')); ?></span>
                                    <?php endif; ?>
                                    <div class="mt-4 d-flex">
                                        <a href="<?php echo e(route('user.withdraw')); ?>" class="btn btn-md sp_btn_danger me-xxl-3 me-2"><i class="las la-minus-circle fs-lg"></i> <?php echo e(__('Withdraw')); ?></a>
                                        <a href="<?php echo e(route('user.deposit')); ?>" class="btn btn-md sp_btn_success ms-xxl-3 ms-2"><i class="las la-plus-circle fs-lg"></i> <?php echo e(__('Deposit')); ?></a>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <ul class="recent-transaction-list mt-4">
                                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="single-recent-transaction">
                                        <div class="content">
                                            <h6 class="title"><?php echo e($trans->details); ?></h6>
                                            <span><?php echo e($trans->created_at->format('d F, Y')); ?></span>
                                        </div>
                                        <p class="recent-transaction-amount <?php echo e($trans->type == '+' ?  "sp_text_success" : 'sp_text_danger'); ?>"><?php echo e(Config::formatter($trans->amount)); ?></p>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                </ul>
                                <a href="<?php echo e(route('user.transaction.log')); ?>" class="btn sp_theme_btn mt-4 w-100"><i class="fas fa-rocket me-2"></i> <?php echo e(__('View All Transaction')); ?></a>
                            </div>
                        </div>
                        
                    </div>
                </div>

                
            </div>
        </div>

        <div class="col-xxl-3 col-xl-4 d-custom-right">
            <div class="d-right-wrapper">
                <div class="d-xl-block d-none">
                    <div class="row g-sm-4 g-3">
                        <div class="col-xl-12 col-lg-6">
                            <div class="d-card user-card not-hover"> 
                                <div class="text-center">
                                    <h5 class="user-card-title"><?php echo e(__('Total Balance')); ?></h5>
                                    <h4 class="d-card-balance mt-xxl-3 mt-2"><?php echo e(Config::formatter($totalbalance)); ?></h4>
                                    <p class="mb-0 mt-2"><?php echo e(__('Freeze Balance')); ?>: <?php echo e(Config::formatter(auth()->user()->freeze_balance)); ?></p>
                                    <?php if(auth()->user()->is_account_freeze): ?>
                                        <span class="badge badge-danger mt-2"><?php echo e(__('Account Freeze')); ?></span>
                                    <?php endif; ?>
                                    <div class="mt-4 d-flex">
                                        <a href="<?php echo e(route('user.withdraw')); ?>" class="btn btn-md sp_btn_danger me-xxl-3 me-2"><i class="las la-minus-circle fs-lg"></i> <?php echo e(__('Withdraw')); ?></a>
                                        <a href="<?php echo e(route('user.deposit')); ?>" class="btn btn-md sp_btn_success ms-xxl-3 ms-2"><i class="las la-plus-circle fs-lg"></i> <?php echo e(__('Deposit')); ?></a>
                                    </div>
                                </div>

                                

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-12 col-lg-6">
            <?php echo $__env->make('frontend.partials.coin_market_board', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
        </div>
                    
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            'use strict'

            var copyButton = document.querySelector('.copy');
            var copyInput = document.querySelector('.copy-text');
            if (copyButton && copyInput) {
                copyButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    copyInput.select();
                    document.execCommand('copy');
                });
                copyInput.addEventListener('click', function() {
                    this.select();
                });
            }

            var copyButton2 = document.querySelector('.copy2');
            var copyInput2 = document.querySelector('.copy-text2');
            if (copyButton2 && copyInput2) {
                copyButton2.addEventListener('click', function(e) {
                    e.preventDefault();
                    copyInput2.select();
                    document.execCommand('copy');
                });
                copyInput2.addEventListener('click', function() {
                    this.select();
                });
            }

            var expirationDate = new Date('<?php echo e($plan_expired_at); ?>');

            function updateCountdown() {
                var now = new Date();
                var timeLeft = expirationDate - now;

                if (timeLeft < 0) {
                    $('#countdownTwo').html(`
                        <p class="upgrade-text"><i class="fas fa-rocket"></i> Please Upgrade Your Plan To Get Signals</p>
                    `);
                } else {
                    var daysLeft = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    var hoursLeft = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutesLeft = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    var secondsLeft = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    $('#countdownTwo').html(`
                        <h5 class="d-left-countdown-title"><?php echo e(__('plan expired at :')); ?></h5>
                        <div class="countdown-wrapper">
                            <p class="countdown-single">${daysLeft}<span>D</span></p>
                            <p class="countdown-single">${hoursLeft}<span>H</span></p>
                            <p class="countdown-single">${minutesLeft}<span>M</span></p>
                            <p class="countdown-single">${secondsLeft}<span>S</span></p>
                        </div>
                    `);
                }
            }

            // updateCountdown();
            // setInterval(updateCountdown, 1000);

            const marketCaps = {
                BTC: '$496.14M',
                ETH: '$161.66M',
                DOGE: '$2.52M',
                TRX: '$2.14M',
                XRP: '$4.91M',
                LTC: '$1.37M'
            };

            function formatPrice(price) {
                const value = Number(price || 0);
                if (value >= 1000) {
                    return value.toFixed(2);
                }
                if (value >= 1) {
                    return value.toFixed(4);
                }
                return value.toFixed(6);
            }

            function getMinimumStep(price) {
                if (price >= 1000) {
                    return 0.01;
                }
                if (price >= 1) {
                    return 0.0001;
                }
                return 0.000001;
            }

            function renderCoinRow(row, nextPrice) {
                const price = Number(nextPrice || 0);
                if (!price) {
                    return;
                }

                const priceElement = row.querySelector('.coin-market-row__price');
                const usdElement = row.querySelector('.coin-market-row__price-usd');
                const metaElement = row.querySelector('.coin-market-row__meta');
                const previousPrice = parseFloat(row.dataset.displayPrice || row.dataset.basePrice || '0');

                metaElement.textContent = marketCaps[row.dataset.symbol] || '$0.00M';
                priceElement.textContent = formatPrice(price);
                usdElement.textContent = '$' + formatPrice(price);

                row.classList.remove('is-up', 'is-down');
                if (previousPrice) {
                    row.classList.add(price >= previousPrice ? 'is-up' : 'is-down');
                }

                row.dataset.displayPrice = price;
            }

            function fetchCoinBoard(boardElement) {
                const rows = boardElement.querySelectorAll('.coin-market-row');

                rows.forEach(function(row) {
                    const symbol = row.dataset.symbol;

                    $.ajax({
                        url: "<?php echo e(route('user.current-price')); ?>",
                        method: 'GET',
                        data: {
                            currency: symbol,
                            _t: Date.now()
                        },
                        cache: false,
                        success: function(response) {
                            const price = parseFloat(response || 0);

                            if (!price) {
                                return;
                            }

                            row.dataset.basePrice = price;

                            if (!row.dataset.displayPrice) {
                                renderCoinRow(row, price);
                            }
                        }
                    });
                });
            }

            function animateCoinBoard(boardElement) {
                const rows = boardElement.querySelectorAll('.coin-market-row');

                rows.forEach(function(row) {
                    const basePrice = parseFloat(row.dataset.basePrice || '0');
                    const displayedPrice = parseFloat(row.dataset.displayPrice || row.dataset.basePrice || '0');

                    if (!basePrice || !displayedPrice) {
                        return;
                    }

                    const minStep = getMinimumStep(basePrice);
                    const drift = (Math.random() - 0.5) * Math.max(basePrice * 0.00035, minStep * 3);
                    const targetPrice = basePrice + drift;
                    const nextPrice = Math.max(minStep, displayedPrice + ((targetPrice - displayedPrice) * 0.35));

                    renderCoinRow(row, nextPrice);
                });
            }

            $('.js-coin-board').each(function() {
                fetchCoinBoard(this);
            });

            setInterval(function() {
                $('.js-coin-board').each(function() {
                    animateCoinBoard(this);
                });
            }, 700);

            setInterval(function() {
                $('.js-coin-board').each(function() {
                    fetchCoinBoard(this);
                });
            }, 8000);
        })
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .coin-market-card {
            padding: 0;
            overflow: hidden;
        }

        .coin-market-card__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 24px 18px;
        }

        .coin-market-card__title {
            font-size: 1.05rem;
            color: #111827;
        }

        .coin-market-card__subtitle {
            color: rgba(17, 24, 39, 0.52);
            font-size: 0.88rem;
        }

        .coin-market-card__pulse {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 8px rgba(34, 197, 94, 0.12);
        }

        .coin-market-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 24px;
            border-top: 1px solid rgba(17, 24, 39, 0.08);
        }

        .coin-market-row__asset {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .coin-market-row__icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #0b1020;
            background: linear-gradient(135deg, #ffd166, #ff9f43);
        }

        .coin-market-row__icon--eth { background: linear-gradient(135deg, #c4b5fd, #60a5fa); }
        .coin-market-row__icon--doge { background: linear-gradient(135deg, #fde68a, #fbbf24); }
        .coin-market-row__icon--trx { background: linear-gradient(135deg, #fb7185, #ef4444); }
        .coin-market-row__icon--xrp { background: linear-gradient(135deg, #93c5fd, #38bdf8); }
        .coin-market-row__icon--ltc { background: linear-gradient(135deg, #e5e7eb, #94a3b8); }

        .coin-market-row__pair {
            font-size: 1.05rem;
            font-weight: 700;
            color: #111827;
        }

        .coin-market-row__pair span,
        .coin-market-row__meta,
        .coin-market-row__price-usd {
            color: rgba(17, 24, 39, 0.55);
        }

        .coin-market-row__price-wrap {
            text-align: right;
        }

        .coin-market-row__price {
            font-size: 1.45rem;
            font-weight: 700;
            color: #19d3a2;
            line-height: 1.1;
        }

        .coin-market-row.is-down .coin-market-row__price {
            color: #ff5c73;
        }

        @media (max-width: 575px) {
            .coin-market-row {
                padding: 16px;
            }

            .coin-market-row__price {
                font-size: 1.15rem;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make(Config::theme() . 'layout.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/light/user/dashboard.blade.php ENDPATH**/ ?>