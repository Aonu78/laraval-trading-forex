

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="sp_site_card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <?php echo e(__('Current Balance: ')); ?> <span
                            class="color-change"><?php echo e(Config::formatter(auth()->user()->balance, 2)); ?></span>
                    </h4>
                    <p class="mb-0 mt-2">
                        <?php echo e(__('Freeze Balance: ')); ?> <span class="color-change"><?php echo e(Config::formatter(auth()->user()->freeze_balance, 2)); ?></span>
                    </p>
                </div>
                <div class="card-body">
                    <?php if(auth()->user()->is_account_freeze): ?>
                        <div class="alert alert-danger">
                            <?php echo e(__('Your account is frozen. You cannot request a withdrawal right now.')); ?>

                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <?php echo csrf_field(); ?>

<?php if(isset($hasPending) && $hasPending): ?>
    <div class="alert alert-warning mb-3">
        <?php echo e(__('You have pending withdrawal request(s). Please wait 10-20 minutes for processing.')); ?>

    </div>
    <div class="table-responsive mb-4">
        <h6 class="mb-2">Recent Pending Withdrawals:</h6>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th><?php echo e(__('Trx')); ?></th>
                    <th><?php echo e(__('Date')); ?></th>
                    <th><?php echo e(__('Method')); ?></th>
                    <th><?php echo e(__('Getable Amount')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pendingWithdraws->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($withdraw->trx); ?></td>
                        <td><?php echo e($withdraw->created_at->format('d M Y')); ?></td>
                        <td><?php echo e(optional($withdraw->withdrawMethod)->name ?? 'N/A'); ?></td>
                        <td><?php echo e(number_format($withdraw->total, 2)); ?></td>
                        <td><span class="badge badge-warning"><?php echo e(__('Pending')); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center"><?php echo e(__('No pending found')); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <a href="<?php echo e(route('user.withdraw.pending')); ?>" class="btn btn-sm btn-outline-warning mb-3"><?php echo e(__('View All Pending')); ?></a>
<?php endif; ?>

<div class="form-group">
                            <label for=""><?php echo e(__('Withdraw Method')); ?></label>
                            <select name="method" id="" class="form-select">
                                <option value="" selected><?php echo e(__('Select Method')); ?></option>
                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($withdraw->id); ?>"
                                        data-url="<?php echo e(route('user.withdraw.fetch', $withdraw->id)); ?>">
                                        <?php echo e($withdraw->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="row appendData"></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 withdraw-ins">
            <div class="sp_site_card">
                <div class="card-header">
                    <h4 class="mb-0"><?php echo e(__('Withdraw Instruction')); ?></h4>
                </div>
                <div class="card-body">
                    <p class="instruction"></p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            'use strict'

            $('select[name=method]').on('change', function() {
                if ($(this).val() == '') {
                    $('.appendData').addClass('d-none');
                    $('.instruction').text('');
                    return;
                }

                <?php if(auth()->user()->is_account_freeze): ?>
                    $('.appendData').addClass('d-none');
                    return;
                <?php endif; ?>

                $('.appendData').removeClass('d-none');
                getData($('select[name=method] option:selected').data('url'))
            })

            $(document).on('keyup', '.amount', function() {
                const withdraw_charge_type = $('.withdraw_charge_type').text();

                if ($(this).val() == '') {
                    $('.final_amo').val(0);
                    return
                }

                const charge = $('.charge').val();

                if (withdraw_charge_type.localeCompare("percent") == 1) {
                    let percentAmount = Number.parseFloat($(this).val()) - Number.parseFloat((charge * $(
                        this).val()) / 100);

                    $('.final_amo').val(percentAmount.toFixed(2));
                    return
                }
                if (withdraw_charge_type.localeCompare("fixed") == 1) {

                    let totalAmount = Number.parseFloat($(this).val()) - Number.parseFloat(charge);

                    $('.final_amo').val(totalAmount).toFixed(2);
                }



            })

            function getData(url) {
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {

                        $('.instruction').html(response.instruction)
                        let html = `

                                <div class="col-md-12 mb-3 mt-3">
                                    <label for=""><?php echo e(__('Withdraw Amount')); ?> <span class="sp_text_danger">*</span></label>
                                    <input type="text" name="amount" class="form-control amount" required>
                                    <p class="text-small color-change mb-0 mt-1"><span><?php echo e(__('Min Amount ')); ?>  ${Number.parseFloat(response.min_withdraw_amount).toFixed(2)}</span> <span><?php echo e(__('& Max Amount')); ?> ${Number.parseFloat(response.max_withdraw_amount).toFixed(2)}</span></p>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label><?php echo e(__('Withdraw Charge')); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control charge" value="${Number.parseFloat(response.charge).toFixed(2)}" required disabled>
                                        <div class="input-group-text sp_bg_main text-white border-0">
                                            <span class="withdraw_charge_type">${response.type}<span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for=""><?php echo e(__('Getable Amount')); ?> <span class="sp_text_danger">*</span></label>
                                    <input type="text" name="final_amo" class="form-control final_amo" required readonly>
                                </div>

                                <div class="col-md-12 mb-3 d-none">
                                    <label for=""><?php echo e(__('Account Email / Wallet Address')); ?> <span class="sp_text_danger">*</span></label>
                                    <input type="text" name="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for=""><?php echo e(__('Currency')); ?></label>
                                    <select name="currency" class="form-select">
                                        <option value="<?php echo e(Config::config()->currency); ?>" selected><?php echo e(Config::config()->currency); ?></option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for=""><?php echo e(__('Account Holder Name')); ?></label>
                                    <input type="text" name="account_holder_name" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for=""><?php echo e(__('Bank Name')); ?></label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for=""><?php echo e(__('Bank Account Number')); ?></label>
                                    <input type="text" name="bank_account_number" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for=""><?php echo e(__('IFSC Code')); ?></label>
                                    <input type="text" name="ifsc_code" class="form-control">
                                </div>

                              

                                <div class="col-md-12 mt-2">
                                   <button class="btn sp_theme_btn w-100" type="submit" <?php echo e(auth()->user()->is_account_freeze ? 'disabled' : ''); ?>><?php echo e(__('Withdraw Now')); ?></button>
                                </div>
                   `;

                        $('.appendData').html(html);
                    }
                })
            }
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make(Config::theme() . 'layout.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/light/user/withdraw/index.blade.php ENDPATH**/ ?>