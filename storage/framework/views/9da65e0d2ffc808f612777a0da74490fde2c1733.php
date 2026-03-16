<?php $__env->startSection('element'); ?>

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-header justify-content-end">
                    <a href="<?php echo e(route('admin.plan.index')); ?>" class="btn btn-outline-primary btn-sm"><i
                            class="fa fa-arrow-left mr-2"></i><?php echo e(__('Back')); ?></a>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.plan.update', $plan->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row">

                            <div class="col-sm-6 my-3">
                                <label class="form-label"><?php echo e(__('Plan Name')); ?> <code>*</code> </label>
                                <input type="text" class="form-control" name="plan_name" value="<?php echo e($plan->name); ?>">

                            </div>

                            <div class="col-md-6 my-3">
                                <label class="form-label"><?php echo e(__('Plan Type')); ?> <code>*</code> </label>
                                <select name="plan_type" class="form-control" id="plan_type">
                                    <option value="unlimited" <?php echo e($plan->plan_type == 'unlimited' ? 'selected' : ''); ?>>
                                        <?php echo e(__('Unlimited')); ?></option>
                                    <option value="limited" <?php echo e($plan->plan_type == 'limited' ? 'selected' : ''); ?>>
                                        <?php echo e(__('Limited')); ?></option>
                                </select>
                            </div>

                            <div class="col-md-6 my-3" id="append">
                                <label class="form-label"><?php echo e(__('Price Type')); ?> <code>*</code> </label>
                                <select name="price_type" class="form-control" id="price_type">
                                    <option value="free" <?php echo e($plan->price_type == 'free' ? 'selected' : ''); ?>>
                                        <?php echo e(__('Free')); ?></option>
                                    <option value="paid" <?php echo e($plan->price_type == 'paid' ? 'selected' : ''); ?>>
                                        <?php echo e(__('Paid')); ?></option>
                                </select>
                            </div>

                            <?php if($plan->plan_type === 'limited'): ?>
                                <div class="col-md-6 my-3" id="duration">
                                    <label class="form-label"><?php echo e(__('Duration (In Days)')); ?></label>
                                    <input type="number" class="form-control" name="duration"
                                        value="<?php echo e($plan->duration); ?>">
                                </div>
                            <?php endif; ?>

                            <?php if($plan->price_type === 'paid'): ?>
                                <div class="col-md-6 my-3" id="price_append">
                                    <label class="form-label"><?php echo e(__('Plan Price')); ?></label>
                                    <input type="number" class="form-control" name="price" value="<?php echo e(Config::formatOnlyNumber($plan->price)); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="col-sm-12 my-4">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <h4 class="mb-0">
                                        <?php echo e(__('Create Features')); ?>

                                    </h4>
                                    <button type="button" class="btn btn-primary btn-sm add">
                                         <i class="las la-plus-circle"></i>
                                         <?php echo e(__('Add New')); ?>

                                    </button>
                                </div>
                                <div class="row" id="feature">
                                    <?php if($plan->feature != null): ?>
                                        <?php $__currentLoopData = $plan->feature; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 remove mt-3">
                                                <div class="input-group">
                                                    <input type="text" name="feature[]" class="form-control"
                                                        value="<?php echo e($feature); ?>">
                                                    <button class="btn btn-outline-secondary border-left-0 delete"><i
                                                            class="fa fa-times text-danger"></i></button>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div><!-- Col -->
                        </div>



                        <div class="row">
                            <div class="col-xxl-5-items col-lg-4 col-sm-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="whatsapp" class="custom-control-input" id="whatsapp"
                                        <?php echo e($plan->whatsapp ? 'checked' : ''); ?>>
                                    <label class="custom-control-label"
                                        for="whatsapp"><?php echo e(__('Whatsapp notification')); ?></label>
                                </div>
                            </div>

                            <div class="col-xxl-5-items col-lg-4 col-sm-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="telegram" class="custom-control-input" id="telegram"
                                        <?php echo e($plan->telegram ? 'checked' : ''); ?>>
                                    <label class="custom-control-label"
                                        for="telegram"><?php echo e(__('Telegram notification')); ?></label>
                                </div>
                            </div>

                            <div class="col-xxl-5-items col-lg-4 col-sm-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="email" class="custom-control-input" id="email"
                                        <?php echo e($plan->email ? 'checked' : ''); ?>>
                                    <label class="custom-control-label"
                                        for="email"><?php echo e(__('Email notification')); ?></label>
                                </div>
                            </div>

                            <div class="col-xxl-5-items col-lg-4 col-sm-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="sms" class="custom-control-input" id="sms"
                                        <?php echo e($plan->sms ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="sms"><?php echo e(__('SMS notification')); ?></label>
                                </div>
                            </div>

                            <div class="col-xxl-5-items col-lg-4 col-sm-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="dashboard" class="custom-control-input" id="dashboard"
                                        <?php echo e($plan->dashboard ? 'checked' : ''); ?>>
                                    <label class="custom-control-label"
                                        for="dashboard"><?php echo e(__('Dashboard notification')); ?></label>
                                </div>
                            </div>
                        </div><!-- Row -->
                        <button type="submit" class="btn btn-primary mt-4"><?php echo e(__('Plan Update')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {

            'use strict'

            let price = `
            <div class="col-md-6 my-3" id="price_append">
                    <label class="form-label"><?php echo e(__('Plan Price')); ?></label>
                    <input type="number" class="form-control" name="price">
                
            </div>
        `;

            let duration = `
            <div class="col-md-6 my-3" id="duration">
                    <label class="form-label"><?php echo e(__('Duration (In Days)')); ?></label>
                    <input type="number" class="form-control" name="duration">
                </div>
        `;

            let feature = `
            <div class="col-md-6 remove mt-3">
                <div class="input-group">
                    <input type="text" name="feature[]" class="form-control">
                    <button class="btn btn-outline-secondary border-left-0 delete"><i class="fa fa-times text-danger"></i></button>
                </div>
            </div>
        `;


            $('#plan_type').on('change', function() {
                let value = $(this).val();

                if (value === 'limited') {
                    $('#append').after(duration)

                    return
                }

                $('#duration').remove();

            })

            $('#price_type').on('change', function() {
                let value = $(this).val();

                if (value === 'paid') {
                    $('#append').after(price)

                    return
                }

                $('#price_append').remove()

            })


            $('.add').on('click', function() {
                $('#feature').append(feature)
            })

            $(document).on('click', '.delete', function() {
                $(this).closest('.remove').remove()
            })



        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/plan/edit.blade.php ENDPATH**/ ?>