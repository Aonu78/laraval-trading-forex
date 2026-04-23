<div class="row g-sm-4 g-3">
    <div class="col-12">
        <div class="site-card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="mb-0"><?php echo e(__('Notifications')); ?></h4>
                <?php if($notifications->whereNull('read_at')->count() > 0): ?>
                    <form action="<?php echo e(route('user.notifications.mark-all')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn main-btn btn-sm">
                            <?php echo e(__('Mark All As Read')); ?>

                        </button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="notification-item <?php echo e($notification->read_at ? 'is-read' : 'is-unread'); ?>">
                        <div>
                            <h6 class="mb-1">
                                <?php echo e($notification->data['title'] ?? __('Notification')); ?>

                            </h6>
                            <p class="mb-2"><?php echo e($notification->data['message'] ?? ''); ?></p>
                            <small class="text-muted">
                                <?php echo e(__('From')); ?>: <?php echo e($notification->data['sent_by'] ?? 'admin'); ?>

                                • <?php echo e($notification->created_at->diffForHumans()); ?>

                            </small>
                        </div>
                        <div class="notification-actions">
                            <?php if(!empty($notification->data['url'])): ?>
                                <a href="<?php echo e($notification->data['url']); ?>" class="btn main-btn btn-sm">
                                    <?php echo e(__('Open')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if(!$notification->read_at): ?>
                                <form action="<?php echo e(route('user.notifications.mark-read', $notification->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        <?php echo e(__('Mark Read')); ?>

                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <p class="mb-0"><?php echo e(__('No notifications found')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($notifications->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($notifications->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('style'); ?>
    <style>
        .notification-item {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            margin-bottom: 16px;
            background: #fff;
        }

        .notification-item.is-unread {
            border-left: 4px solid #f59e0b;
        }

        .notification-item.is-read {
            border-left: 4px solid #10b981;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        @media (max-width: 767px) {
            .notification-item {
                flex-direction: column;
            }
        }
    </style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/partials/user_notifications.blade.php ENDPATH**/ ?>