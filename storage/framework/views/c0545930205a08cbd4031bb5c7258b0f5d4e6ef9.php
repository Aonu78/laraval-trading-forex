

<?php $__env->startSection('element'); ?>
    <form action="<?php echo e(route('admin.password.reset')); ?>" method="POST" class="cmn-form mt-30">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="email" class="text-white"><?php echo e(__('Email')); ?></label>
            <input type="email" name="email" class="form-control b-radius--capsule" id="username"
                value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__('Enter your email')); ?>">
            <i class="las la-user input-icon"></i>
        </div>

        <?php if(Config::config()->allow_recaptcha == 1): ?>
            <div class="form-group">
                <script src="https://www.google.com/recaptcha/api.js"></script>
                <div class="g-recaptcha" data-sitekey="<?php echo e(Config::config()->recaptcha_key); ?>" data-callback="verifyCaptcha">
                </div>
                <div id="g-recaptcha-error"></div>
            </div>
        <?php endif; ?>


        <div class="form-group text-right">
            <a href="<?php echo e(route('admin.login')); ?>" class="text--small"><i
                    class="fas fa-lock mr-2"></i><?php echo e(__('Back to Login')); ?></a>
        </div>

        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary btn-block">
                <?php echo e(__('Send Reset Code')); ?>

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

<?php echo $__env->make('backend.auth.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\p\Downloads\Compressed\signal_v5\main\resources\views/backend/auth/forgot-password.blade.php ENDPATH**/ ?>