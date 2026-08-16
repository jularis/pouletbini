<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get("Nom - Adresse"); ?></th>
                                    <th><?php echo app('translator')->get('Email-Phone'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Date de création'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $magasins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $magasin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold d-block"><?php echo e(__($magasin->name)); ?></span>
                                            <small class="text-muted"> <i><?php echo e(__($magasin->address)); ?></i></span>
                                        </td>
                                        <td>
                                            <span class="d-block"><?php echo e($magasin->email); ?></span>
                                            <span><?php echo e($magasin->phone); ?></span>
                                        </td>
                                        <td>  <?php echo $magasin->statusBadge; ?> </td>
                                        <td>
                                            <span class="d-block"><?php echo e(showDateTime($magasin->created_at)); ?></span>
                                            <span><?php echo e(diffForHumans($magasin->created_at)); ?></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary editMagasin"
                                                data-id="<?php echo e($magasin->id); ?>" data-name="<?php echo e($magasin->name); ?>"
                                                data-email="<?php echo e($magasin->email); ?>" data-phone="<?php echo e($magasin->phone); ?>"
                                                data-address="<?php echo e($magasin->address); ?>"><i
                                                    class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?></button>

                                            <?php if($magasin->status == Status::DISABLE): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success  confirmationBtn"
                                                    data-action="<?php echo e(route('admin.magasin.status', $magasin->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer ce magasin?'); ?>">
                                                    <i class="la la-eye"></i><?php echo app('translator')->get("Activer"); ?>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="<?php echo e(route('admin.magasin.status', $magasin->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir désactiver ce magasin?'); ?>">
                                                    <i class="la la-eye-slash"></i><?php echo app('translator')->get("Désactiver"); ?>
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
                        </table><!-- table end -->
                    </div>
                </div>
                <?php if($magasins->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($magasins)); ?>

                    </div>
                <?php endif; ?>
            </div><!-- card end -->
        </div>
    </div>

    <div id="magasinModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get("Créer un nouveau Magasin"); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="<?php echo e(route('admin.magasin.store')); ?>" class="resetForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom'); ?></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get("Adresse Email"); ?></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Téléphone'); ?></label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>


                        <div class="form-group">
                            <label><?php echo app('translator')->get('Adresse'); ?></label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get("Envoyer"); ?></button>
                    </div>
                </form>
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
    <button class="btn  btn-outline--primary h-45 addNewMagasin"><i class="las la-plus"></i><?php echo app('translator')->get("Créer un nouveau"); ?></button>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.addNewMagasin').on('click', function() {
                $('.resetForm').trigger('reset');
                $('#magasinModel').modal('show');
            });
            $('.editMagasin').on('click', function() {
                let title = "<?php echo app('translator')->get('Update Magasin'); ?>";
                var modal = $('#magasinModel');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let address = $(this).data('address');
                modal.find('.modal-title').text(title)
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=email]').val(email);
                modal.find('input[name=phone]').val(phone);
                modal.find('input[name=address]').val(address);
                modal.modal('show');
            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/poulnymi/new.pouletbini.com/core/resources/views/admin/magasin/index.blade.php ENDPATH**/ ?>