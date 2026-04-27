<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="<?php echo e($page->seo_description ?? Config::config()->seo_description); ?>" />
    <meta name="keywords" content="<?php echo e(implode(',', $page->seo_keywords ?? Config::config()->seo_tags)); ?> ">

    <title><?php echo e(Config::config()->appname); ?></title>

    <link rel="stylesheet" href="<?php echo e(optional(Config::config()->fonts)->heading_font_url); ?>">
    <link rel="stylesheet" href="<?php echo e(optional(Config::config()->fonts)->paragraph_font_url); ?>">

    <link rel="shortcut icon" type="image/png" href="<?php echo e(Config::getFile('icon', Config::config()->favicon, true)); ?>">

    <link rel="stylesheet" href="<?php echo e(Config::cssLib('frontend', 'lib/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(Config::cssLib('frontend', 'all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(Config::cssLib('frontend', 'line-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(Config::cssLib('frontend', 'lib/slick.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(Config::cssLib('frontend', 'lib/odometer.css')); ?>">

    <?php if(Config::config()->alert === 'izi'): ?>
        <link rel="stylesheet" href="<?php echo e(Config::cssLib('frontend', 'izitoast.min.css')); ?>">
    <?php elseif(Config::config()->alert === 'toast'): ?>
        <link href="<?php echo e(Config::cssLib('frontend', 'toastr.min.css')); ?>" rel="stylesheet">
    <?php else: ?>
        <link href="<?php echo e(Config::cssLib('frontend', 'sweetalert.min.css')); ?>" rel="stylesheet">
    <?php endif; ?>

    <link href="<?php echo e(Config::cssLib('frontend', 'main.css')); ?>" rel="stylesheet">

    <?php
        $heading = optional(Config::config()->fonts)->heading_font_family;
        $paragraph = optional(Config::config()->fonts)->paragraph_font_family;
    ?>

    <style>
        :root {
            --h-font: <?=$heading ?>;
            --p-font: <?=$paragraph ?>;
        }
    </style>
<?php
    $noCssRoutes = [
        'home',
        'user.current-price',
        'ticker',
        'trading-interest',
        'change-language',
        'blog.details',
        'links',
    ];

    $noCssPages = ['about', 'contact', 'why-us', 'blog'];
?>

<?php if(
    !request()->routeIs($noCssRoutes) &&
    !in_array(request()->segment(1), $noCssPages)
): ?>
    <style>
        body.light-force-mobile {
            background: #e2e8f0;
        }
        .user-sidebar, aside{
            display: none !important;  
        }
        body.light-force-mobile .body-content-area,
        body.light-force-mobile .sp_footer,
        body.light-force-mobile .sp_header {
            width: min(100%, 430px);
            margin-left: auto;
            margin-right: auto;
        }

        body.light-force-mobile .body-content-area,
        body.light-force-mobile .sp_footer {
            background: #ffffff;
            box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.08), 0 24px 50px rgba(15, 23, 42, 0.12);
        }

        body.light-force-mobile .sp_header {
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        body.light-force-mobile .container,
        body.light-force-mobile .container-sm,
        body.light-force-mobile .container-md,
        body.light-force-mobile .container-lg,
        body.light-force-mobile .container-xl,
        body.light-force-mobile .container-xxl {
            max-width: 100% !important;
            padding-left: 14px;
            padding-right: 14px;
        }

        body.light-force-mobile .sp_header_info_bar,
        body.light-force-mobile .sp_banner_el,
        body.light-force-mobile .header-top-right {
            display: none !important;
        }

        body.light-force-mobile .sp_header_main {
            padding: 12px 0;
        }

        body.light-force-mobile .navbar-expand-xl .navbar-toggler {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }

        body.light-force-mobile .navbar-expand-xl .navbar-collapse {
            flex-basis: 100%;
        }

        body.light-force-mobile .navbar-expand-xl .navbar-collapse:not(.show) {
            display: none !important;
        }

        body.light-force-mobile .navbar-expand-xl .navbar-collapse.show {
            display: block !important;
        }

        body.light-force-mobile .sp_header .sp_site_menu {
            display: block;
            margin-top: 12px;
        }

        body.light-force-mobile .sp_header .sp_site_menu li {
            display: block;
            margin-left: 0 !important;
        }

        body.light-force-mobile .sp_header .sp_site_menu li a {
            display: block;
            padding: 10px 0;
        }

        body.light-force-mobile .navbar-action {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            margin-top: 14px;
        }

        body.light-force-mobile .navbar-action .btn,
        body.light-force-mobile .navbar-action a {
            width: 100%;
            text-align: center;
            margin: 0 !important;
        }

        body.light-force-mobile .sp_banner,
        body.light-force-mobile .sp_page_banner {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        body.light-force-mobile .sp_banner .sp_banner_title {
            font-size: 2rem;
            line-height: 1.2;
        }

        body.light-force-mobile .sp_banner_thumb,
        body.light-force-mobile .sp_banner .row > div[class*="col-"],
        body.light-force-mobile section .row > div[class*="col-"],
        body.light-force-mobile footer .row > div[class*="col-"] {
            margin-bottom: 18px;
        }

        body.light-force-mobile .row {
            --bs-gutter-x: 1rem;
        }

        body.light-force-mobile [class*="col-"] {
            width: 100%;
            flex: 0 0 100%;
        }

        body.light-force-mobile .sp_page_breadcrumb {
            flex-wrap: wrap;
            gap: 6px;
        }
    </style>
<?php endif; ?>
    <?php echo $__env->yieldPushContent('external-css'); ?>

    <?php echo $__env->yieldPushContent('style'); ?>


</head>

<body class="light-force-mobile">


    <?php if(Config::config()->preloader_status): ?>
        <div class="preloader-holder">
            <div class="preloader">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
            </div>
        </div>
    <?php endif; ?>


    <?php if(Config::config()->analytics_status): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(Config::config()->analytics_key); ?>"></script>
        <script>
            'use strict'
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag("js", new Date());
            gtag("config", "<?php echo e(Config::config()->analytics_key); ?>");
        </script>
    <?php endif; ?>

    <?php if(Config::config()->allow_modal): ?>
        <?php echo $__env->make('cookieConsent::index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <div class="body-content-area">
        <?php if(request()->routeIs('home')): ?>
            <?php echo $__env->make(Config::theme() . 'widgets.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php echo $__env->make(Config::theme() . 'layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php if(!request()->routeIs('home')): ?>
            <?php echo $__env->make(Config::theme() . 'widgets.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <?php echo $__env->make(Config::theme() . 'widgets.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="<?php echo e(Config::jsLib('frontend', 'lib/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/slick.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/wow.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/jquery.paroller.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/TweenMax.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/odometer.min.js')); ?>"></script>
    <script src="<?php echo e(Config::jsLib('frontend', 'lib/viewport.jquery.js')); ?>"></script>



    <?php if(Config::config()->alert === 'izi'): ?>
        <script src="<?php echo e(Config::jsLib('frontend', 'izitoast.min.js')); ?>"></script>
    <?php elseif(Config::config()->alert === 'toast'): ?>
        <script src="<?php echo e(Config::jsLib('frontend', 'toastr.min.js')); ?>"></script>
    <?php else: ?>
        <script src="<?php echo e(Config::jsLib('frontend', 'sweetalert.min.js')); ?>"></script>
    <?php endif; ?>

    <script src="<?php echo e(Config::jsLib('frontend', 'main.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('script'); ?>


    <?php if(Config::config()->twak_allow): ?>
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = "<?php echo e(Config::config()->twak_key); ?>";
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    <?php endif; ?>

    <script>
        $(function() {
            'use strict'

            $(document).on('submit', '#subscribe', function(e) {
                e.preventDefault();
                const email = $('.subscribe-email').val();
                var url = "<?php echo e(route('subscribe')); ?>";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        email: email,
                        _token: "<?php echo e(csrf_token()); ?>"
                    },
                    success: (response) => {

                        $('.subscribe-email').val('');

                        <?php echo $__env->make(Config::theme() . 'layout.ajax_alert', [
                            'message' => 'Successfully Subscribe',
                            'message_error' => '',
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    },
                    error: () => {

                        <?php if(Config::config()->alert === 'izi'): ?>
                            iziToast.error({
                                position: 'topRight',
                                message: "Email is Required",
                            });
                        <?php elseif(Config::config()->alert === 'toast'): ?>
                            toastr.error("Email is Required", {
                                positionClass: "toast-top-right"

                            })
                        <?php else: ?>
                            Swal.fire({
                                icon: 'error',
                                title: "Email is Required"
                            })
                        <?php endif; ?>
                    }
                })

            });

            var url = "<?php echo e(route('change-language')); ?>";

            $(".changeLang").on('change', function() {
               
                if ($(this).val() == '') {
                    return false;
                }
                window.location.href = url + "?lang=" + $(this).val();
            });

        })
    </script>


    <?php echo $__env->make('alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</body>

</html>
<?php /**PATH C:\xampp\htdocs\laraval-trading-forex\resources\views/frontend/light/layout/master.blade.php ENDPATH**/ ?>