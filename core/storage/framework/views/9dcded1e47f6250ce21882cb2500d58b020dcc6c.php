<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Staff'); ?></th>
                                    <th><?php echo app('translator')->get('Email'); ?></th>
                                    <th><?php echo app('translator')->get("Crée le"); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span><?php echo e(__($staff->fullname)); ?></span>
                                            <br>
                                            <a href="<?php echo e(route('manager.staff.edit', encrypt($staff->id))); ?>">
                                                <span>@</span><?php echo e(__($staff->username)); ?>

                                            </a>
                                        </td>

                                        <td>
                                            <span><?php echo e($staff->email); ?><br><?php echo e($staff->mobile); ?></span>
                                        </td>

                                        <td>
                                            <?php echo e(showDateTime($staff->created_at)); ?>

                                        </td>

                                        <td>
                                            <?php
                                                echo $staff->statusBadge;
                                            ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo e(route('manager.staff.edit', encrypt($staff->id))); ?>"
                                                class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?></a>
                                                    <a href="<?php echo e(route('manager.staff.login', $staff->id)); ?>"
                                                class="btn btn-sm btn-outline--success" target="_blank"><i
                                                    class="las la-sign-in-alt"></i>
                                                <?php echo app('translator')->get('Login'); ?></a>
                                            <?php if($staff->status == Status::BAN_USER): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="<?php echo e(route('manager.staff.status', $staff->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Are you sure to active this staff?'); ?>">
                                                    <i class="la la-eye"></i> <?php echo app('translator')->get("Activé"); ?>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger  confirmationBtn"
                                                    data-action="<?php echo e(route('manager.staff.status', $staff->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Are you sure to ban this staff?'); ?>">
                                                    <i class="la la-eye-slash"></i> <?php echo app('translator')->get("Suspendu"); ?>
                                                </button>
                                            <?php endif; ?>
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
                <?php if($staffs->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($staffs)); ?>

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
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Recherche ici']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Recherche ici']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <a href="<?php echo e(route('manager.staff.create')); ?>" class="btn  btn-outline--primary h-45 addNewMagasin"><i
            class="las la-plus"></i><?php echo app('translator')->get("Créer un nouveau"); ?></a>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/staff/index.blade.php ENDPATH**/ ?>