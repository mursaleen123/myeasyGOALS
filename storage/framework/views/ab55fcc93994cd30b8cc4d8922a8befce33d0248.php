<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', '')); ?></title>
    <link rel="icon" href="<?php echo e(asset('admin/assets/media/logos/favicon.ico')); ?>" sizes="192x192" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="<?php echo e(asset('admin/assets/plugins/global/plugins.bundle.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('admin/assets/css/style.bundle.css')); ?>" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <style>
        .bg-permit-place {
            background-color: #000;
        }
    </style>
</head>

<body>
    <div id="kt_body" class="bg-body">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="<?php echo e(asset('admin/assets/plugins/global/plugins.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/scripts.bundle.js')); ?>"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="<?php echo e(asset('admin/assets/js/custom/authentication/sign-in/general.js')); ?>"></script>
    <!--end::Page Custom Javascript-->
    <!--end::Javascript-->
</body>

</html>
<?php /**PATH C:\xampp\htdocs\myeasyGOALS\resources\views/layouts/auth.blade.php ENDPATH**/ ?>