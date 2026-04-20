


<?php $__env->startSection('element'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <?php echo $__env->make('backend.logs.tab_list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <form action="" method="GET">
                        <div class="input-group">
                            <a href="javascript:void(0)"
                                class="btn btn-outline-secondary rounded-0 daterange-btn btn-sm btn-d icon-left btn-icon filterData"><i
                                    class="fas fa-calendar"></i> <?php echo e(__('Filter By Date')); ?>

                            </a>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Ref')); ?></th>
                                    <th><?php echo e(__('User')); ?></th>
                                    <th><?php echo e(__('Currency Sym')); ?></th>
                                    <th><?php echo e(__('Trade Price At')); ?></th>

                                    <th><?php echo e(__('Trade Type')); ?></th>
                                    <th><?php echo e(__('Trade Close At')); ?></th>
                                    <th><?php echo e(__('Profit/Loss')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                </tr>
                            </thead>

                            <tbody id="filter_data">
                                <?php $__empty_1 = true; $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $trade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(strtoupper($trade->ref)); ?></td>
                                        <td>

                                            <a href="<?php echo e(route('admin.user.details', $trade->user->id)); ?>">
                                                <img src="<?php echo e(Config::getFile('user', $trade->user->image, true)); ?>"
                                                    alt="" class="image-table">
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
                            </tbody>

                        </table>
                    </div>
                </div>

                <?php if($trades->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($trades->links()); ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .ranges {
            padding: 10px !important;
            margin-top: 10px !important;
        }

        .daterangepicker .ranges li.active {
            background-color: #6777ee !important;
        }

        .daterangepicker .ranges li:hover {
            background-color: #f5f5f5 !important;
            color: #6777ee;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px #ddd solid;
            border-top: 4px #068cfa solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes  sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('external-style'); ?>
    <link rel="stylesheet" href="<?php echo e(Config::cssLib('backend', 'daterangepicker.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('external-script'); ?>
    <script src="<?php echo e(Config::jsLib('backend', 'moment.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('backend', 'daterangepicker.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            'use strict'

            $('.daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            }, function(start, end) {
                $('.daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'))
            });


            $('.ranges ul li').each(function(index) {
                $(this).on('click', function() {
                    let key = $(this).data('range-key')
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "<?php echo e(route('admin.trade')); ?>",
                        data: {
                            key: key
                        },
                        method: "GET",
                        success: function(response) {

                            $('#filter_data').html(response);
                        },
                        complete: function() {
                            $("#overlay").fadeOut(300);
                        }

                    })


                })
            })


            $(document).on('click', '.applyBtn', function() {

                let date = $('.drp-selected').text()

                let key = 'Custom Range'

                $("#overlay").fadeIn(300);

                $.ajax({
                    url: "<?php echo e(route('admin.trade')); ?>",
                    data: {
                        key: key,
                        date: date
                    },
                    method: "GET",
                    success: function(response) {

                        $('#filter_data').html(response);
                    },
                    complete: function() {
                        $("#overlay").fadeOut(300);
                    }

                })
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/backend/logs/trade_log.blade.php ENDPATH**/ ?>