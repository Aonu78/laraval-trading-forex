<!-- how work section start -->
<section class="work-section sp_pt_120 sp_pb_120">
    <div class="sp_container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="sp_theme_top  wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s">
                    <h2 class="sp_theme_top_title"><?= Config::colorText(optional($content)->title, optional($content)->color_text_for_title) ?></span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row gy-5 justify-content-center">
            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-4 col-md-6">
                    <div class="sp_work_item">
                        <div class="sp_work_number">
                            <?php echo e($loop->iteration); ?>

                        </div>
                        <div class="sp_work_content">
                            <h4 class="title"><?php echo e(Config::trans($item->content->title)); ?></h4>
                            <p class="mt-2"><?php echo e(Config::trans($item->content->description)); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<!-- how work section end --><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/frontend/blue/widgets/how_works.blade.php ENDPATH**/ ?>