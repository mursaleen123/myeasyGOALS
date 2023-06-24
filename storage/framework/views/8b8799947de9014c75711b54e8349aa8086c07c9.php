<?php $__env->startSection('content'); ?>
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative bg-permit-place">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                    <!--begin::Content-->
                    <div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">

                        <!--begin::Title-->
                        <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #fff;">Welcome to <?php echo e(config('app.name')); ?>

                        </h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <p class="fw-bold fs-2" style="color: #fff;">Transform your dreams into reality
                            <br />with effortless goal achievement.
                        </p>
                        <!--end::Description-->
                    </div>
                    <!--end::Content-->
                    <!--begin::Illustration-->
                    
                    <!--end::Illustration-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                        <!--begin::Heading-->
                        <div>
                            <br>
                            <br>
                            <br>
                            <br>
                        </div>
                        <div class="text-center mb-10">
                            <!--begin::Logo-->
                            <a href="<?php echo e(route('index')); ?>">
                                <h1><?php echo e(config('app.name')); ?></h1>
                            </a>
                            <!--end::Logo-->
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3"><?php echo e(__('Reset Password')); ?></h1>
                            <!--end::Title-->
                            <?php if(session('status')): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo e(session('status')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <!--begin::Heading-->
                        <a href="<?php echo e(route('api.reset', [$token, $email])); ?>" target="_blank">Reset Password</a>
                        
                        
                        <div>
                            <br>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!--end::Wrapper-->
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.admin.admin-footer','data' => []]); ?>
<?php $component->withName('admin.admin-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Main-->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\myeasyGOALS\resources\views/auth/passwords/api/email.blade.php ENDPATH**/ ?>