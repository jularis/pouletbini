 
<?php $__env->startSection('panel'); ?>
<div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Categorie'); ?></th>
                                    <th><?php echo app('translator')->get('Nom'); ?></th>  
                                    <th><?php echo app('translator')->get('Prix Unitaire'); ?></th> 
                                    <th><?php echo app('translator')->get('Quantite'); ?></th>
                                    <th><?php echo app('translator')->get('Quantite Utilisée'); ?></th>
                                    <th><?php echo app('translator')->get('Quantite Restante'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th> 
                                    <th><?php echo app('translator')->get('Last Update'); ?></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                    <td>
                                            <span><?php echo e(__($produit->categorie->name)); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e(__($produit->name)); ?></span>
                                        </td>
                                        
                                        <td>
                                            <span><?php echo e(showAmount($produit->price)); ?> <?php echo e(__($general->cur_text)); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e(showAmount($produit->quantity)); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e(showAmount($produit->quantity_use)); ?></span>
                                        </td> 
                                        <td>
                                            <span class="fw-bold"><?php echo e(showAmount($produit->quantity - $produit->quantity_use)); ?></span>
                                        </td> 
                                        <td>
                                            <?php
                                                echo $produit->statusBadge;
                                            ?>
                                        </td>

                                        <td>
                                            <span class="d-block"><?php echo e(showDateTime($produit->updated_at)); ?></span>
                                            <span><?php echo e(diffForHumans($produit->updated_at)); ?></span>
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
                <?php if($produits->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($produits)); ?>

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
 
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.addCategorie').on('click', function() {
                $('#categorieModel').modal('show');
            });

            $('.updateCategorie').on('click', function() {
                var modal = $('#updateCategorieModel');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=price]').val($(this).data('price'));
                modal.find('input[name=quantity]').val($(this).data('quantity'));
                modal.find('select[name=categorie]').val($(this).data('categorie'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/categorie/produit.blade.php ENDPATH**/ ?>