<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="sp_site_card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__(optional($gateway->parameter)->name . ' Information')); ?></h5>
                </div>
                <div class="card-body">
                    <?php if($gateway->parameter->gateway_type == 'crypto'): ?>
                        <ul class="list-group">
                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Gateway Name')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->name ?? 'N/A'); ?></span>
                            </li>

                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Method Currency')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->gateway_currency); ?></span>
                            </li>

                            <li class="list-group-item"> 
                                <img src="<?php echo e(Config::getFile('gateways', $gateway->parameter->qr_code, true )); ?>" alt="">
                            </li>

                            <li class="list-group-item ">
                               
                                <span class="w-100"><?= clean($gateway->parameter->payment_instruction) ?></span>

                            </li>

                        </ul>
                    <?php else: ?>
                        <ul class="list-group">
                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Gateway Name')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->name ?? 'N/A'); ?></span>
                            </li>

                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Account Number')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->account_number ?? 'N/A'); ?></span>
                            </li>

                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Routing Number')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->routing_number ?? 'N/A'); ?></span>
                            </li>

                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Branch Name')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->branch_name ?? 'N/A'); ?></span>
                            </li>

                            <li class="list-group-item  d-flex justify-content-between">
                                <span><?php echo e(__('Method Currency')); ?></span>
                                <span><?php echo e(optional($gateway->parameter)->gateway_currency); ?></span>
                            </li>

                            <li class="list-group-item ">
                               
                                <span class="w-100"><?= clean($gateway->parameter->payment_instruction) ?></span>

                            </li>

                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="sp_site_card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Payment Information')); ?></h5>
                </div>

                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item   d-flex justify-content-between">
                            <span><?php echo e(__('Gateway Name')); ?>:</span>

                            <span><?php echo e(str_replace('_', ' ', $deposit->gateway->name)); ?></span>

                        </li>
                        <li class="list-group-item   d-flex justify-content-between">
                            <span><?php echo e(__('Amount')); ?>:</span>
                            <span><?php echo e(Config::formatter($deposit->amount)); ?></span>
                        </li>

                        <li class="list-group-item   d-flex justify-content-between">
                            <span><?php echo e(__('Charge')); ?>:</span>
                            <span><?php echo e(Config::formatter($deposit->charge)); ?></span>
                        </li>

                        <li class="list-group-item   d-flex justify-content-between">
                            <span><?php echo e(__('Conversion Rate')); ?>:</span>
                            <span><?php echo e('1 ' . Config::config()->currency . ' = ' . Config::formatOnlyNumber($deposit->rate)); ?></span>
                        </li>

                        <li class="list-group-item   d-flex justify-content-between">
                            <span><?php echo e(__('Total Payable Amount')); ?>:</span>
                            <span><?php echo e(Config::formatOnlyNumber($deposit->total) . ' ' . $deposit->gateway->parameter->gateway_currency); ?></span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="sp_site_card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Requirments')); ?></h5>
                </div>

                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                           
                            <?php $__currentLoopData = $gateway->parameter->user_proof_param; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $proof = (array) $proof
                            ?>
                          

                                <?php if($proof['type'] == 'text'): ?>
                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-2 mt-2"><?php echo e(__($proof['field_name'])); ?></label>
                                        <input type="text"
                                            name="<?php echo e(strtolower(str_replace(' ', '_', $proof['field_name']))); ?>"
                                            class="form-control"
                                            <?php echo e($proof['validation'] == 'required' ? 'required' : ''); ?>>
                                    </div>
                                <?php endif; ?>
                                <?php if($proof['type'] == 'textarea'): ?>
                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-2 mt-2"><?php echo e(__($proof['field_name'])); ?></label>
                                        <textarea name="<?php echo e(strtolower(str_replace(' ', '_', $proof['field_name']))); ?>" class="form-control"
                                            <?php echo e($proof['validation'] == 'required' ? 'required' : ''); ?>></textarea>
                                    </div>
                                <?php endif; ?>

                                <?php if($proof['type'] == 'file'): ?>
                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-2 mt-2"><?php echo e(__($proof['field_name'])); ?></label>
                                        <input type="file"
                                            name="<?php echo e(strtolower(str_replace(' ', '_', $proof['field_name']))); ?>"
                                            class="form-control"
                                            <?php echo e($proof['validation'] == 'required' ? 'required' : ''); ?>>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <div class="form-group">
                                <button class="btn sp_theme_btn mt-4" type="submit"><?php echo e(__('Pay Now')); ?></button>

                            </div>


                        </div>



                    </form>



                </div>

            </div>




        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(Config::theme(). 'layout.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/frontend/blue/user/gateway/offline.blade.php ENDPATH**/ ?>