<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                <th><?php echo app('translator')->get('Unite'); ?></th>
                                    <th><?php echo app('translator')->get('Nom'); ?></th>
                                    <th><?php echo app('translator')->get('Prix'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                    <td><?php echo e(__($categorie->unite->name)); ?></td>
                                        <td><?php echo e(__($categorie->name)); ?></td>
                                        <td><?php echo e(__($categorie->price)); ?></td>
                                        <td> <?php  echo $categorie->statusBadge; ?> </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary updateCategorie"
                                                data-id="<?php echo e($categorie->id); ?>"
                                                data-unite="<?php echo e($categorie->unite_id); ?>" 
                                                data-price="<?php echo e($categorie->price); ?>" 
                                                data-name="<?php echo e($categorie->name); ?>"><i
                                                    class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?></button>

                                            <?php if($categorie->status == Status::DISABLE): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="<?php echo e(route('admin.livraison.categorie.status', $categorie->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer ce categorie?'); ?>">
                                                    <i class="la la-eye"></i> <?php echo app('translator')->get("Activer"); ?>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="<?php echo e(route('admin.livraison.categorie.status', $categorie->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir désactiver ce categorie?'); ?>">
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
                        </table>
                    </div>
                </div>
                <?php if($categories->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($categories)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="categorieModel" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Ajouter Categorie'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i></button>
                </div>
                <form action="<?php echo e(route('admin.livraison.categorie.store')); ?>" class="resetForm" method="POST">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name='id'>
                   
                    <div class="modal-body">
                    <div class="form-group">
                            <label><?php echo app('translator')->get('Unite'); ?></label>
                            <select class="form-control" name="unite" required>
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <?php $__currentLoopData = $unites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unite->id); ?>"><?php echo e(__($unite->name)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom de la Categorie'); ?></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Prix'); ?></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="price" required>
                                <span class="input-group-text"><?php echo e(__($general->cur_text)); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100"><?php echo app('translator')->get("Envoyer"); ?></button>
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
    <button class="btn btn-sm btn-outline--primary addCategorie"><i class="las la-plus"></i><?php echo app('translator')->get("Créer un nouveau"); ?></button>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.addCategorie').on('click', function() {
                $('#categorieModel').modal('show');
                $('.resetForm').trigger('reset');
            });

            $('.updateCategorie').on('click', function() {
                let title = "Update Categorie"
                let id = $(this).data('id');
                let name = $(this).data('name');
                var modal = $('#categorieModel');
                modal.find('.modal-title').text(title);
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=price]').val($(this).data('price'));
                modal.find('select[name=unite]').val($(this).data('unite'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/categorie/categorie.blade.php ENDPATH**/ ?>