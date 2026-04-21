<?php $__empty_1 = true; $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $trade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td><?php echo e(strtoupper($trade->ref)); ?></td>
        <td>
            <a href="<?php echo e(route('admin.user.details', $trade->user->id)); ?>">
                <img src="<?php echo e(Config::getFile('user', $trade->user->image, true)); ?>" alt="" class="image-table">
                <span>
                    <?php echo e($trade->user->username); ?>

                </span>
            </a>
        </td>
        <td><?php echo e($trade->currency); ?></td>
        <td><?php echo e(Config::formatter($trade->current_price)); ?></td>

        <td>
            <?php if($trade->trade_type == 'buy'): ?>
                <i class="fas fa-arrow-alt-circle-up text-success"></i>
                <?php echo e($trade->trade_type); ?>

            <?php else: ?>
                <i class="fas fa-arrow-alt-circle-down text-danger"></i>
                <?php echo e($trade->trade_type); ?>

            <?php endif; ?>
        </td>

        <td>
            <?php echo e($trade->trade_stop_at); ?>

        </td>

        <td>
            <?php if($trade->profit_type == '+'): ?>
                <span class="text-success font-weight-bolder"><?php echo e(__('+' . $trade->profit_amount)); ?></span>
            <?php elseif($trade->profit_type == '-'): ?>
                <span class="text-danger font-weight-bolder"><?php echo e(__('-' . $trade->loss_amount)); ?></span>
            <?php endif; ?>
        </td>

        <td>
            <form action="<?php echo e(route('admin.trade.result-mode', $trade->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <select name="result_mode" class="form-control form-control-sm"
                    onchange="toggleTradeWinAmount(this)" <?php if($trade->status): ?> disabled <?php endif; ?>>
                    <option value="default" <?php if(($trade->result_mode ?? 'default') === 'default'): ?> selected <?php endif; ?>><?php echo e(__('Default')); ?></option>
                    <option value="force_win" <?php if(($trade->result_mode ?? 'default') === 'force_win'): ?> selected <?php endif; ?>><?php echo e(__('Force Win')); ?></option>
                    <option value="force_loss" <?php if(($trade->result_mode ?? 'default') === 'force_loss'): ?> selected <?php endif; ?>><?php echo e(__('Force Loss')); ?></option>
                </select>
                <div class="trade-win-amount-wrapper mt-2 <?php if(($trade->result_mode ?? 'default') !== 'force_win'): ?> d-none <?php endif; ?>">
                    <input type="number" name="force_profit_amount" class="form-control form-control-sm"
                        step="0.01" min="0"
                        value="<?php echo e(old('force_profit_amount', $trade->force_profit_amount)); ?>"
                        placeholder="<?php echo e(__('Win Amount')); ?>"
                        <?php if(($trade->result_mode ?? 'default') !== 'force_win' || $trade->status): ?> disabled <?php endif; ?>>
                </div>
                <?php if(!$trade->status): ?>
                    <button type="submit" class="btn btn-sm btn-primary mt-2"><?php echo e(__('Save')); ?></button>
                <?php endif; ?>
            </form>
        </td>

        <td>
            <?php if($trade->force_profit_amount !== null): ?>
                <span class="font-weight-bolder"><?php echo e(Config::formatter($trade->force_profit_amount)); ?></span>
            <?php else: ?>
                <span class="text-muted"><?php echo e(__('Auto')); ?></span>
            <?php endif; ?>
        </td>

        <td>
            <?php if($trade->status): ?>
                <span class="text-success "><i class="far fa-check-circle font-weight-bolder"></i></span>
            <?php else: ?>
                <span class="text-danger "><i class="fas fa-spinner fa-spin font-weight-bolder"></i></span>
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
<?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/backend/logs/_trade_rows.blade.php ENDPATH**/ ?>