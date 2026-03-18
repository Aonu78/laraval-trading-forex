

<?php $__env->startSection('element'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header site-card-header justify-content-between">
                    <div class="card-header-left">
                        <h4><?php echo e(__('All Pages')); ?></h4>
                    </div>
                    <div class="card-header-right">
                        <a href="<?php echo e(route('admin.frontend.pages.create')); ?>" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus mr-2"></i>
                            <?php echo e(__('Create Page')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table" id="example">
                            <thead>
                                <tr>

                                    <th><?php echo e(__('Page Name')); ?></th>
                                    <th><?php echo e(__('Page Order')); ?></th>
                                    <th><?php echo e(__('Dropdown')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>

                                        <td>
                                            <?php echo e($page->name); ?>

                                        </td>

                                        <td>
                                            <?php echo e($page->order); ?>

                                        </td>

                                        <td>
                                            <?php if($page->is_dropdown): ?>
                                                <span class="badge badge-primary"><?php echo e(__('Yes')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e(__('No')); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($page->status): ?>
                                                <span class="badge badge-success"><?php echo e(__('Active')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e(__('In active')); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo e(route('admin.frontend.pages.edit', $page)); ?>"
                                                class="btn btn-sm btn-outline-primary edit">
                                                <i class="fa fa-pen"></i>
                                            </a>
                                            <?php if(!$loop->first): ?>
                                                <a href="#" class="btn btn-sm btn-outline-danger delete"
                                                    data-url="<?php echo e(route('admin.frontend.pages.delete', $page)); ?>"><i
                                                        class="fa fa-trash"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center text-danger" colspan="100%">
                                            <?php echo e(__('No Data Found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if($pages->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($pages->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



    <div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('Delete Page')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <?php echo csrf_field(); ?>

                        <p><?php echo e(__('Are You Sure To Delete Pages')); ?>?</p>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary text-dark mr-3"
                                data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                            <button type="submit" class="btn btn-sm btn-danger"><?php echo e(__('Delete Page')); ?></button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        'use strict'
        $(function() {
            $('.delete').on('click', function() {
                const modal = $('#deleteModal');

                modal.find('form').attr('action', $(this).data('url'))
                modal.modal('show')
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/page/index.blade.php ENDPATH**/ ?>