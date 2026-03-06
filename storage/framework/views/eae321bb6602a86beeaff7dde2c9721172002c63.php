
<?php $__env->startSection('element'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header site-card-header justify-content-between">
                    <div class="card-header-left">
                        <form action="" method="get">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="search time frame">
                                <button class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="card-header-right">
                        <button class="btn btn-sm btn-primary add"> <i class="fa fa-plus"></i> <?php echo e(__('Create Time Frame')); ?></button>
                    </div>
                </div>
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table student-data-table m-t-20">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Sl')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $frames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frame): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($frame->name); ?></td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="status" <?php echo e($frame->status ? 'checked' : ''); ?>

                                                    class="custom-control-input status" id="status<?php echo e($frame->id); ?>"
                                                    data-id="<?php echo e($frame->id); ?>"
                                                    data-route="<?php echo e(route('admin.frames.changestatus', $frame)); ?>">
                                                <label class="custom-control-label"
                                                    for="status<?php echo e($frame->id); ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary edit"
                                                data-pair="<?php echo e($frame); ?>"
                                                data-url="<?php echo e(route('admin.frames.update', $frame->id)); ?>"><i class="far fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger delete"
                                                data-url="<?php echo e(route('admin.frames.destroy', $frame->id)); ?>"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="100%" class="text-center"><?php echo e(__('No Time Frame Found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                </div>

                <?php if($frames->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($frames->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">

            <form action="<?php echo e(route('admin.frames.store')); ?>" method="post">

                <?php echo csrf_field(); ?>

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(__('Create Signal Time Frame')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for=""><?php echo e(__('Frame Name')); ?></label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for=""><?php echo e(__(' Status')); ?></label>
                            <select name="status" class="form-control">
                                <option value="1"><?php echo e(__('Active')); ?></option>
                                <option value="0"><?php echo e(__('Disable')); ?></option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i>
                            <?php echo e(__('Create')); ?></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>


    <div class="modal fade" id="edit" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">

            <form action="" method="post">

                <?php echo csrf_field(); ?>

                <?php echo method_field('PUT'); ?>

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(__('Update Time Frame')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for=""><?php echo e(__('Frame Name')); ?></label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for=""><?php echo e(__(' Status')); ?></label>
                            <select name="status" class="form-control">
                                <option value="1"><?php echo e(__('Active')); ?></option>
                                <option value="0"><?php echo e(__('Disable')); ?></option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i>
                            <?php echo e(__('Update')); ?></button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>


    <div class="modal fade" id="delete" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">

            <form action="" method="post">

                <?php echo csrf_field(); ?>

                <?php echo method_field('DELETE'); ?>

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(__('Delete Time Frame')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <p><?php echo e(__('Are You sure to Delete this time frame ?')); ?></p>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i>
                            <?php echo e(__('Delete')); ?></button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo e(__('Close')); ?></button>
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

            $('.add').on('click', function() {
                const modal = $('#add')

                modal.modal('show')
            })


            $('.edit').on('click', function() {
                const modal = $('#edit')

                modal.find('form').attr('action', $(this).data('url'))

                modal.find('input[name=name]').val($(this).data('pair').name)

                modal.find('select[name=status]').val($(this).data('pair').status)

                modal.modal('show')
            })

            $('.delete').on('click', function() {
                const modal = $('#delete')

                modal.find('form').attr('action', $(this).data('url'));

                modal.modal('show');
            })

            $('.status').on('change', function() {

                let id = $(this).data('id');
                let route = $(this).data('route');

                $.ajax({

                    url: route,
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>"
                    },
                    success: function(response) {
                        <?php echo $__env->make('backend.layout.ajax_alert', [
                            'message' => 'Successfully change status',
                            'message_error' => '',
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    }

                })




            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/frame/index.blade.php ENDPATH**/ ?>