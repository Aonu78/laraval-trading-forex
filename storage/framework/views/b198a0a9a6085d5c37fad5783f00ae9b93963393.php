<section class="benefit-section sp_pt_120 sp_pb_120">
    <div class="sp_container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="sp_theme_top  wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s">
                    <h2 class="sp_theme_top_title">
                        <?= Config::trans($content->title) ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row gy-4 align-items-center">
            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xxl-4 col-xl-6 col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
                <div class="sp_benefit_item">
                    <div class="sp_benefit_icon">
                        <img src="<?php echo e(Config::getFile('benefits', $item->content->image_one)); ?>" alt="image">
                    </div>
                    <div class="sp_benefit_content">
                        <h4 class="title"><?php echo e(Config::trans($item->content->title)); ?></h4>
                        <p class="mt-2"><?php echo e(Config::trans($item->content->description)); ?></p>
                    </div>
                </div><!-- sp_benefit_item end -->
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<!-- benefit section end --><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/frontend/blue/widgets/benefits.blade.php ENDPATH**/ ?>