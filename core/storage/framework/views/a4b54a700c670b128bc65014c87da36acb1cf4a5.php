
<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <input type="hidden" name="table" value="fermes" />
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Recherche par Mot(s) clé(s)'); ?></label>
                                <input type="text" name="search" value="<?php echo e(request()->search); ?>" class="form-control">
                            </div>
                             
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Date'); ?></label>
                                <input name="date" type="text" class="dates form-control"
                                    placeholder="<?php echo app('translator')->get('Date de début - Date de fin'); ?>" autocomplete="off" value="<?php echo e(request()->date); ?>">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    <?php echo app('translator')->get('Filter'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Nom'); ?></th>
                                    <th><?php echo app('translator')->get('Lieu'); ?></th>
                                    <th><?php echo app('translator')->get('Responsable'); ?></th>
                                    <th><?php echo app('translator')->get('Contact'); ?></th> 
                                    <th><?php echo app('translator')->get('Date de création'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $fermes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ferme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?php echo e($ferme->nom); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e($ferme->lieu); ?></span>
                                        </td> 
                                        <td>
                                            <span class="small">
                                                <?php echo e($ferme->responsable); ?>  
                                            </span>
                                        </td>
                                       
                                        <td>
                                            <span><?php echo e($ferme->contact); ?></span>
                                        </td> 
                                        <td>
                                            <span class="d-block"><?php echo e(showDateTime($ferme->created_at)); ?></span>
                                            <span><?php echo e(diffForHumans($ferme->created_at)); ?></span>
                                        </td>
                                        <td> <?php echo $ferme->statusBadge; ?> </td>
                                        <td>

                                            <button type="button" class="btn btn-sm btn-outline--primary"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i><?php echo app('translator')->get('Action'); ?>
                                            </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="<?php echo e(route('admin.ferme.edit', $ferme->id)); ?>"
                                                    class="dropdown-item"><i class="la la-pen"></i><?php echo app('translator')->get('Edit'); ?></a>
                                                <?php if($ferme->status == Status::DISABLE): ?>
                                                    <button type="button" class="confirmationBtn  dropdown-item"
                                                        data-action="<?php echo e(route('admin.ferme.status', $ferme->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure to enable this parcelle?'); ?>">
                                                        <i class="la la-eye"></i> <?php echo app('translator')->get('Activé'); ?>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="confirmationBtn dropdown-item"
                                                        data-action="<?php echo e(route('admin.ferme.status', $ferme->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure to disable this parcelle?'); ?>">
                                                        <i class="la la-eye-slash"></i> <?php echo app('translator')->get('Désactivé'); ?>
                                                    </button>
                                                <?php endif; ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($fermes->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($fermes)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
 
    <?php if (isset($component)) { $__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b = $component; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\ConfirmationModal::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b)): ?>
<?php $component = $__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b; ?>
<?php unset($__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <a href="<?php echo e(route('admin.ferme.create')); ?>" class="btn  btn-outline--primary h-45 addNewCooperative">
        <i class="las la-plus"></i><?php echo app('translator')->get('Ajouter nouveau'); ?>
    </a>
     

<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
    <style>
        .table-responsive {
            overflow-x: auto;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/fcadmin/css/vendor/datepicker.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('assets/fcadmin/js/vendor/datepicker.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/fcadmin/js/vendor/datepicker.fr.js')); ?>"></script>
<script src="<?php echo e(asset('assets/fcadmin/js/vendor/datepicker.en.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script> 
        (function($) {
            "use strict";
 
            $('.dates').datepicker({
                maxDate: new Date(),
                range: true,
                multipleDatesSeparator: "-",
                language: 'fr'
            });
 

        })(jQuery)

        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/ferme/index.blade.php ENDPATH**/ ?>