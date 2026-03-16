

<?php $__env->startSection('element'); ?>
    <form action="<?php echo e(route('admin.password.verify.code')); ?>" method="POST" class="cmn-form mt-30">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label class="text-dark"><?php echo e(__('Verification Code')); ?></label>
            <input type="text" name="code" id="code" class="form-control" placeholder="Place your Code">
        </div>

        <?php if(Config::config()->allow_recaptcha == 1): ?>
            <div class="form-group">
                <script src="https://www.google.com/recaptcha/api.js"></script>
                <div class="g-recaptcha" data-sitekey="<?php echo e(Config::config()->recaptcha_key); ?>" data-callback="verifyCaptcha">
                </div>
                <div id="g-recaptcha-error"></div>
            </div>
        <?php endif; ?>
        
        <div class="form-group d-flex justify-content-end align-items-center">
           

            <a href="<?php echo e(route('admin.send.again')); ?>" class="text-primary text--small"
                id="try"><?php echo e(__('Try to send again')); ?></a>

        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" tabindex="4">
                <?php echo e(__('Verify Code')); ?>

            </button>
        </div>
    </form>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('script'); ?>
    <script>
        "use strict";

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    "<span class='text-danger'><?php echo e(__('Captcha field is required.')); ?></span>";
                return false;
            }
            return true;
        }

        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.auth.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/auth/code_verify.blade.php ENDPATH**/ ?>