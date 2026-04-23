<div class="d-card not-hover coin-market-card js-coin-board">
    <div class="coin-market-card__header">
        <div>
            <h5 class="coin-market-card__title mb-0"><?php echo e(__('Live Market')); ?></h5>
            <p class="coin-market-card__subtitle mb-0"><?php echo e(__('Popular coins with changing prices')); ?></p>
        </div>
        <span class="coin-market-card__pulse"></span>
    </div>

    <div class="coin-market-list">
        <?php $__currentLoopData = ['BTC', 'ETH', 'DOGE', 'TRX', 'XRP', 'LTC']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $symbol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="coin-market-row" data-symbol="<?php echo e($symbol); ?>">
                <div class="coin-market-row__asset">
                    <div class="coin-market-row__icon coin-market-row__icon--<?php echo e(strtolower($symbol)); ?>">
                        <?php echo e(substr($symbol, 0, 1)); ?>

                    </div>
                    <div>
                        <h6 class="coin-market-row__pair mb-0"><?php echo e($symbol); ?><span>/USDT</span></h6>
                        <small class="coin-market-row__meta"><?php echo e(__('Loading market cap...')); ?></small>
                    </div>
                </div>
                <div class="coin-market-row__price-wrap">
                    <div class="coin-market-row__price"><?php echo e(__('Loading...')); ?></div>
                    <small class="coin-market-row__price-usd">$0.00</small>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/partials/coin_market_board.blade.php ENDPATH**/ ?>