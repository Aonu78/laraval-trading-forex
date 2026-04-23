<?php
    $plan_expired_at = now();
?>

<?php if(auth()->user()->currentplan): ?>
    <?php
        $is_subscribe = auth()
            ->user()
            ->currentplan()
            ->where('is_current', 1)
            ->first();
        
        if ($is_subscribe) {
            $plan_expired_at = $is_subscribe->plan_expired_at;
        }
    ?>
<?php endif; ?>



<aside class="user-sidebar">
    <a href="<?php echo e(route('user.dashboard')); ?>" class="site-logo">
        <img src="<?php echo e(Config::getFile('dark_logo', Config::config()->dark_logo, true)); ?>" alt="image">
    </a>

    <div class="user-sidebar-bottom">
        
        <div id="countdown"></div>
    </div>

    <ul class="sidebar-menu">
        <li class="<?php echo e(Config::singleMenu('user.dashboard')); ?>"><a href="<?php echo e(route('user.dashboard')); ?>" class="active"><i
                    class="fas fa-home"></i>
                <?php echo e(__('Dashboard')); ?></a></li>
        

        <li><a href="<?php echo e(route('user.trade')); ?>" class="<?php echo e(Config::singleMenu('user.trade')); ?>"><i
                    class="fas fa-chart-line"></i></i>
                <?php echo e(__('Trade')); ?></a></li>

        

        <li class="<?php echo e(Config::singleMenu('user.deposit')); ?>"><a href="<?php echo e(route('user.deposit')); ?>"><i
                    class="fas fa-credit-card"></i><?php echo e(__('Deposit Now')); ?></a></li>

	        <li class="<?php echo e(Config::singleMenu('user.withdraw')); ?>"><a href="<?php echo e(route('user.withdraw')); ?>"><i
	                    class="fas fa-hand-holding-usd"></i> <?php echo e(__('Withdraw')); ?></a></li>

	        <li class="<?php echo e(Config::singleMenu('user.notifications')); ?>"><a href="<?php echo e(route('user.notifications')); ?>"><i
	                    class="fas fa-bell"></i> <?php echo e(__('Notifications')); ?>

	                <?php if(auth()->user()->unreadNotifications()->count() > 0): ?>
	                    <span class="noti-count"><?php echo e(auth()->user()->unreadNotifications()->count()); ?></span>
	                <?php endif; ?>
	            </a></li>

        


        <li
            class="has_submenu <?php echo e(in_array(url()->current(), [route('user.deposit.log'), route('user.withdraw.all'), route('user.invest.log'), route('user.transaction.log'), route('user.transfer_money.log'), route('user.receive_money.log'), route('user.commision'), route('user.subscription')]) ? 'open' : ''); ?>">
            <a href="#0"><i class="fas fa-chart-bar"></i> <?php echo e(__('Report')); ?></a>
            <ul class="submenu">
                <li class="<?php echo e(Config::singleMenu('user.deposit.log')); ?>">
                    <a href="<?php echo e(route('user.deposit.log')); ?>"><?php echo e(__('Deposit Log')); ?></a>
                </li>

                <li class="<?php echo e(Config::singleMenu('user.withdraw.all')); ?>">
                    <a href="<?php echo e(route('user.withdraw.all')); ?>"><?php echo e(__('Withdraw Log')); ?></a>
                </li>

                <li class="<?php echo e(Config::singleMenu('user.invest.log')); ?>">
                    <a href="<?php echo e(route('user.invest.log')); ?>"><?php echo e(__('Investment Log')); ?></a>
                </li>

                <li class="<?php echo e(Config::singleMenu('user.transaction.log')); ?>">
                    <a href="<?php echo e(route('user.transaction.log')); ?>"><?php echo e(__('Transaction Log')); ?></a>
                </li>

                
            </ul>
        </li>

        

        <li class="<?php echo e(Config::singleMenu('user.profile')); ?>"><a href="<?php echo e(route('user.profile')); ?>"><i
                    class="fas fa-user-cog"></i> <?php echo e(__('Profile Settings')); ?></a></li>
        
        <li class="<?php echo e(Config::singleMenu('user.logout')); ?>"><a href="<?php echo e(route('user.logout')); ?>"><i
                    class="fas fa-sign-out-alt"></i> <?php echo e(__('Logout')); ?></a></li>
    </ul>
</aside>

<!-- mobile bottom menu start -->
<div class="mobile-bottom-menu-wrapper">
    <ul class="mobile-bottom-menu">

        <li>
            <a href="<?php echo e(route('user.deposit')); ?>" class="<?php echo e(Config::activeMenu(route('user.deposit'))); ?>">
                <i class="fas fa-wallet"></i>
                <span><?php echo e(__('Deposit')); ?></span>
            </a>
        </li>

        <li>
            <a href="<?php echo e(route('user.trade')); ?>" class="<?php echo e(Config::activeMenu(route('user.trade'))); ?>">
                <i class="fas fa-chart-line"></i>
                <span><?php echo e(__('Trade')); ?></span>
            </a>
        </li>
        
        <li>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="<?php echo e(Config::activeMenu(route('user.dashboard'))); ?>">
                <i class="fas fa-home"></i>
                <span><?php echo e(__('Home')); ?></span>
            </a>
        </li>

	        <li>
	            <a href="<?php echo e(route('user.withdraw')); ?>" class="<?php echo e(Config::activeMenu(route('user.withdraw'))); ?>">
	                <i class="fas fa-hand-holding-usd"></i>
	                <span><?php echo e(__('Withdraw')); ?></span>
	            </a>
	        </li>

	        <li>
	            <a href="<?php echo e(route('user.notifications')); ?>" class="<?php echo e(Config::activeMenu(route('user.notifications'))); ?>">
	                <i class="fas fa-bell"></i>
	                <span><?php echo e(__('Alerts')); ?></span>
	            </a>
	        </li>

	        <li class="sidebar-open-btn">
            <a href="#0" class="">
                <i class="fas fa-bars"></i>
                <span><?php echo e(__('Menu')); ?></span>
            </a>
        </li>
    </ul>
</div>
<!-- mobile bottom menu end -->



<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            'use strict'

            <?php if(Config::config()->exclusive_offer_date): ?>
            var expirationDate = new Date('<?php echo e(Config::config()->exclusive_offer_date); ?>');
            <?php else: ?>
            var expirationDate = new Date('<?php echo e($plan_expired_at); ?>');
            <?php endif; ?>

            function updateCountdown() {
                var now = new Date();
                var timeLeft = expirationDate - now;

                if (timeLeft < 0) {
                    // Expired
                    $('#countdown').html(`
                      <p class="upgrade-text"><i class="fas fa-rocket"></i> <?php echo e(__('Exclusive Offer Expired')); ?></p>
                    `);
                } else {
                    // Active
                    var daysLeft = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    var hoursLeft = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutesLeft = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    var secondsLeft = Math.floor((timeLeft % (1000 * 60)) / 1000);
                    var offerText = '<?php echo e(Config::config()->exclusive_offer); ?>';
                    $('#countdown').html(`
                      <h5 class="user-sidebar-bottom-title">${offerText}</h5>
                      <div class="countdown-wrapper">
                        <p class="countdown-single">
                          ${daysLeft}
                          <span>D</span>
                        </p>
                        <p class="countdown-single">
                          ${hoursLeft}
                          <span>H</span>
                        </p>
                        <p class="countdown-single">
                          ${minutesLeft}
                          <span>M</span>
                        </p>
                        <p class="countdown-single">
                          ${secondsLeft}
                          <span>S</span>
                        </p>
                      </div>
                    `);
                }
            }
            // Call updateCountdown every second
            setInterval(updateCountdown, 1000);
        })
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/light/layout/user_sidebar.blade.php ENDPATH**/ ?>