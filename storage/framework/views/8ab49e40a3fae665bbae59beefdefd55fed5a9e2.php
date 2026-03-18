

<?php $__env->startSection('element'); ?>
    <form action="" method="POST" class="col-md-12">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 <?php echo e($page->name == 'Home' ? 'd-none' : ''); ?>">
                                <label for=""><?php echo e(__('Page Name')); ?></label>
                                <input type="text" name="page" class="form-control" required
                                    value="<?php echo e($page->name); ?>">
                            </div>

                            <div class="form-group col-md-6 ">
                                <label for=""><?php echo e(__('Page Order')); ?></label>
                                <input type="number" name="order" class="form-control" placeholder="Page Order"
                                    value="<?php echo e($page->order); ?>" required>
                            </div>

                            <div class="form-group col-md-6 ">
                                <label for=""><?php echo e(__('Is Dropdown')); ?></label>
                                <select name="is_dropdown" class="form-control">
                                    <option value="1" <?php echo e($page->is_dropdown ? 'selected' : ''); ?>>
                                        <?php echo e(__('Yes')); ?></option>
                                    <option value="0" <?php echo e(!$page->is_dropdown ? 'selected' : ''); ?>>
                                        <?php echo e(__('No')); ?></option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 <?php echo e($page->name == 'home' ? 'd-none' : ''); ?>">
                                <label for=""><?php echo e(__('status')); ?></label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo e($page->status ? 'selected' : ''); ?>>
                                        <?php echo e(__('Active')); ?></option>
                                    <option value="0" <?php echo e($page->status ? '' : 'selected'); ?>>
                                        <?php echo e(__('Inactive')); ?></option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for=""><?php echo e(__('Seo Keywords')); ?></label>
                                <select class="form-control tokenizer" name="keyword[]" multiple="multiple">
                                    <?php $__currentLoopData = $page->seo_keywords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($keyword); ?>" selected><?php echo e($keyword); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for=""><?php echo e(__('Seo Description')); ?></label>
                                <textarea name="seo_description" id="" cols="30" rows="5" class="form-control"><?php echo e($page->seo_description ?? old('seo_description')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="w-100">
                            <select name="" id="widgets" class="form-control">
                            <option value=""><?php echo e(__('Select Widgets')); ?></option>
                            <?php $__currentLoopData = Config::sectionConfig(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $widgets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($widgets); ?>" data-name="<?php echo e($widgets); ?>">
                                    <?php echo e(Config::frontendformatter($widgets)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                       
                            <div class="row mt-4 sp_section_box_wrapper" id="widgetAdd">
                                <?php if($page->widgets): ?>
                                    <?php $__currentLoopData = $page->widgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   
                                        <div class="col-auto mb-4 removeItem">
                                            <div class="popup sp_section_box">
                                                <a href="#" class="close-popup"
                                                    data-item="<?php echo e($sec->sections); ?>">&#x2716;</a>
                                                <span>
                                                    <?php echo e(Config::frontendformatter($sec->sections)); ?>

                                                </span>

                                                <input type="hidden" name="sections[]" value="<?php echo e($sec->sections); ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        

                        <button type="submit" class="btn btn-primary float-right mt-3"><?php echo e(__('Update Page')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('style'); ?>
    <style>
        .sp_section_box {
            width: 124px;
            position: relative;
            padding: 15px 10px;
            border: 1px solid #e5e5e5;
            background-color: #f4faff;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 5px 15px #449ae71a;
            text-align: center;
            height: 100%;
        }

        .popup .close-popup {
            position: absolute; 
            top: -5px; 
            right: -5px; 
            width: 22px; 
            height: 22px; 
            font-size: 14px;
            background-color: #fff; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            color: #ff6046;
            border: 1px solid #e5e5e5;
            border-radius: 50%;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('external-style'); ?>
    <link href="<?php echo e(Config::cssLib('backend', 'select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('external-script'); ?>
    <script src="<?php echo e(Config::jsLib('backend', 'select2.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            'use strict'
            
            $('#widgets').on('change', function() {

                let name = $('#widgets option:selected').data('name')

               

                $('#widgetAdd').append(`


                <div class="col-auto mb-4 removeItem">
                    <div class="popup sp_section_box">
                        <a href="#" class="close-popup"
                            data-item="${name}">&#x2716;</a>
                        <span>
                            ${name.replace(/_/g, ' ')}
                        </span>

                        <input type="hidden" name="sections[]" value="${name}">
                    </div>
                </div>
                
                `);
            })


            $(document).on('click', '.close-popup', function() {
                $(this).parent().parent('.removeItem').remove();
            })



            $(".tokenizer").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/page/edit.blade.php ENDPATH**/ ?>