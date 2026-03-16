
<?php $__env->startSection('element'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header site-card-header justify-content-between">
                    <div class="card-header-left">
                        <form action="" method="get">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="search ID">
                                <select name="type" id="" class="form-control form-control-sm">
                                    <option value="draft"><?php echo e(__('Draft')); ?></option>
                                    <option value="sent"><?php echo e(__('Sent')); ?></option>
                                </select>
                                <button class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="card-header-right">
                        <a class="btn btn-sm btn-primary" href="<?php echo e(route('admin.signals.create')); ?>"> <i class="fa fa-plus"></i>
                            <?php echo e(__('Create Signal')); ?></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table student-data-table m-t-20">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Signal Id')); ?></th>
                                    <th><?php echo e(__('Plans')); ?></th>
                                    <th><?php echo e(__('Pair')); ?></th>
                                    <th><?php echo e(__('Time Frame')); ?></th>
                                    <th><?php echo e(__('Opening point')); ?></th>
                                    <th><?php echo e(__('Stop Loss')); ?></th>
                                    <th><?php echo e(__('Take Profit')); ?></th>
                                    <th><?php echo e(__('Movement Direction')); ?></th>
                                    <th><?php echo e(__('Is Sent')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php $__empty_1 = true; $__currentLoopData = $signals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($signal->id); ?></td>
                                       
                                        <td>
                                            <?php $__currentLoopData = $signal->plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-info"><?php echo e($plan->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php echo e(optional($signal->pair)->name); ?>

                                        </td>

                                        <td>
                                            <?php echo e(optional($signal->time)->name); ?>

                                        </td>

                                        <td>
                                            <?php echo e($signal->open_price); ?>

                                        </td>

                                        <td>
                                            <?php echo e($signal->sl); ?>

                                        </td>

                                        <td>
                                            <?php echo e($signal->tp); ?>

                                        </td>

                                        <td>
                                            <?php if($signal->direction === 'buy'): ?>
                                                <span class="badge badge-success"><?php echo e($signal->direction); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e($signal->direction); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($signal->is_published): ?>
                                                <span class="badge badge-success"><?php echo e(__('Sent')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e(__('Draft')); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo e(route('admin.signals.edit', $signal->id)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <?php if(!$signal->is_published): ?>
                                                <button data-href="<?php echo e(route('admin.signals.sent', $signal->id)); ?>"
                                                    class="btn btn-sm btn-outline-success sent"><i class="fa fa-paper-plane"></i></button>
                                            <?php endif; ?>
                                            <button data-href="<?php echo e(route('admin.signals.destroy', $signal->id)); ?>"
                                                class="btn btn-sm btn-outline-danger delete"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center" colspan="100%"><?php echo e(__('No Signals Found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <?php if($signals->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($signals->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-exclamation-triangle"></i>
                            <?php echo e(__('Confirmation')); ?> !</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <strong><?php echo e(__('Are you sure you want to Delete')); ?> ?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
                            <?php echo e(__('Close')); ?></button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
                            <?php echo e(__('DELETE')); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="sent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-exclamation-triangle"></i>
                            <?php echo e(__('Confirmation')); ?> !</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <strong><?php echo e(__('Are you sure you want to Send This Signal')); ?> ?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
                            <?php echo e(__('Close')); ?></button>
                        <button type="submit" class="btn btn-success">
                            <?php echo e(__('Sent')); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {

            'use strict'

            $('.delete').on('click', function() {
                const modal = $('#delete')

                modal.find('form').attr('action', $(this).data('href'))
                modal.modal('show')
            })

            $('.sent').on('click', function() {
                const modal = $('#sent')

                modal.find('form').attr('action', $(this).data('href'))
                modal.modal('show')
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/signal/index.blade.php ENDPATH**/ ?>