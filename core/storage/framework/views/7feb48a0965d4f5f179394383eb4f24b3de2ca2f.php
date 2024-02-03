<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Magasin'); ?></th>
                                    <th><?php echo app('translator')->get('Manager'); ?></th>
                                    <th><?php echo app('translator')->get("Email - Téléphone"); ?></th>
                                    <th><?php echo app('translator')->get("Crée le"); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $magasinManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?php echo e(__($manager->magasin->name)); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold d-block"><?php echo e($manager->fullname); ?></span>
                                            <span class="small">
                                                <a href="<?php echo e(route('admin.magasin.manager.edit', $manager->id)); ?>">
                                                    <span>@</span><?php echo e($manager->username); ?>

                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span><?php echo e($manager->email); ?><br><?php echo e($manager->mobile); ?></span>
                                        </td>
                                        <td>
                                            <span class="d-block"><?php echo e(showDateTime($manager->created_at)); ?></span>
                                            <span><?php echo e(diffForHumans($manager->created_at)); ?></span>
                                        </td>
                                        <td> <?php echo $manager->statusBadge; ?> </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i><?php echo app('translator')->get('Action'); ?>
                                             </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="<?php echo e(route('admin.magasin.manager.edit', $manager->id)); ?>"
                                                    class="dropdown-item"><i class="la la-pen"></i><?php echo app('translator')->get('Edit'); ?></a>
                                                <a href="<?php echo e(route('admin.magasin.manager.staff.list', $manager->magasin_id)); ?>"
                                                    class="dropdown-item"><i class="las la-user-friends"></i>
                                                    <?php echo app('translator')->get('Liste des Staffs'); ?></a>
                                                <?php if($manager->status == Status::DISABLE): ?>
                                                    <button type="button" class="confirmationBtn  dropdown-item"
                                                        data-action="<?php echo e(route('admin.magasin.manager.status', $manager->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer ce manager?'); ?>">
                                                        <i class="la la-eye"></i> <?php echo app('translator')->get("Activer"); ?>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class=" confirmationBtn   dropdown-item"
                                                        data-action="<?php echo e(route('admin.magasin.manager.status', $manager->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir désactiver ce manager?'); ?>">
                                                        <i class="la la-eye-slash"></i> <?php echo app('translator')->get("Désactiver"); ?>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('admin.magasin.manager.dashboard', $manager->id)); ?>"
                                                    class="dropdown-item" target="_blank"><i class="las la-sign-in-alt"></i>
                                                    <?php echo app('translator')->get('Login'); ?>
                                                </a>
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
                <?php if($magasinManagers->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($magasinManagers)); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Recherche ici...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Recherche ici...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <a href="<?php echo e(route('admin.magasin.manager.create')); ?>" class="btn  btn-outline--primary h-45 addNewMagasin">
        <i class="las la-plus"></i><?php echo app('translator')->get("Créer un nouveau"); ?>
    </a>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
    <style>
        .table-responsive {
            overflow-x: clip;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/manager/index.blade.php ENDPATH**/ ?>