<?php $__env->startSection('content'); ?>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        <?php echo $__env->make('staff.partials.sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('staff.partials.topnav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <?php echo $__env->make('staff.partials.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->yieldContent('panel'); ?>
            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('staff.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/poulnymi/new.pouletbini.com/core/resources/views/staff/layouts/app.blade.php ENDPATH**/ ?>