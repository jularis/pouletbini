<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['placeholder'=>'Search...']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['placeholder'=>'Search...']); ?>
<?php foreach (array_filter((['placeholder'=>'Search...']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<form action="" method="GET">
    <div class="input-group">
        <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control bg--white" data-position='bottom right' placeholder="<?php echo e(__($placeholder)); ?>" autocomplete="off" value="<?php echo e(request()->date); ?>">
        <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
    </div>
</form>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/viseradmin/css/vendor/datepicker.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.en.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            if (!$('.datepicker-here').val()) {
                $('.datepicker-here').datepicker();
            }
        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\pouletbini\core\resources\views/components/date-filter.blade.php ENDPATH**/ ?>