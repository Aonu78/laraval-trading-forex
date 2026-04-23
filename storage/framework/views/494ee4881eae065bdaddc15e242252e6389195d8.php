

<?php $__env->startSection('content'); ?>
    <section class="sp_pt_120 sp_pb_120">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <h3 class="title mb-4"><?php echo e(Config::trans($blog->content->blog_title)); ?></h3>
                    <div class="blog-details-img">
                        <img src="<?php echo e(Config::getFile('blog', $blog->content->image_one)); ?>" alt="image">
                    </div>
                    <div class="blog-details-content">
                        <ul class="sp_blog_meta mb-2 mt-3">
                            <li><i class="far fa-user-circle"></i> <?php echo e(__('by Admin')); ?></li>
                            <li><i class="far fa-clock"></i> <?php echo e($blog->created_at->format('d F, Y')); ?></li>
                        </ul>

                        <p>
                            <?php echo optional($blog->content)->description; ?>
                        </p>
                    </div>

                    <div class="sp_social_links my-4">
                        <?= $shareComponent ?>
                    </div>
                </div>
            </div>

            <div class="my-4">
                <h4><?php echo e(__('Related Post')); ?></h4>
            </div>

            <div class="recent-post-slider">
                <?php $__empty_1 = true; $__currentLoopData = $recentblog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="slide-item">
                        <div class="sp_blog_list_post blog-list-post-two">
                            <div class="sp_blog_list_post_thumb">
                                <img src="<?php echo e(Config::getFile('blog', $item->content->image_one)); ?>" alt="image">
                            </div>
                            <div class="sp_blog_list_post_content">
                                <ul class="sp_blog_meta mb-2">
                                    <li><i class="far fa-user-circle"></i> <?php echo e(__('by Admin')); ?></li>
                                    <li><i class="far fa-clock"></i> <?php echo e($item->created_at->format('d m, Y')); ?></li>
                                </ul>
                                <h4 class="sp_blog_title"><a href="<?php echo e(route('blog.details', [$item->id, Str::slug($item->content->blog_title)])); ?>"><?php echo e(Config::trans($item->content->blog_title)); ?></a>
                                </h4>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(Config::theme() . 'layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/light/pages/blog_details.blade.php ENDPATH**/ ?>