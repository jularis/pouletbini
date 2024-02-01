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
                                    <th><?php echo app('translator')->get('Quantite'); ?></th>
                                    <th><?php echo app('translator')->get('Prix Unitaire'); ?></th> 
                                    <th><?php echo app('translator')->get('Quantite Utilisée'); ?></th>
                                    <th><?php echo app('translator')->get('Quantite Restante'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th> 
                                    <th><?php echo app('translator')->get('Last Update'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
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
                                            <span class="fw-bold"><?php echo e(showAmount($produit->quantity)); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e(showAmount($produit->price)); ?> <?php echo e(__($general->cur_text)); ?></span>
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

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary  updateCategorie"
                                                data-id="<?php echo e($produit->id); ?>" data-name="<?php echo e($produit->name); ?>"
                                                data-price="<?php echo e(getAmount($produit->price)); ?>"
                                                data-categorie="<?php echo e($produit->categorie_id); ?>"><i
                                                    class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?></button>

                                            <?php if($produit->status == Status::DISABLE): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="<?php echo e(route('admin.livraison.categorie.produit.status', $produit->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer ce produit?'); ?>">
                                                    <i class="la la-eye"></i> <?php echo app('translator')->get("Activer"); ?>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="<?php echo e(route('admin.livraison.categorie.produit.status', $produit->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir désactiver ce produit?'); ?>">
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
                <?php if($produits->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($produits)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="categorieModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Ajouter Livraison produit'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="<?php echo e(route('admin.livraison.categorie.produit.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                    <div class="form-group">
                            <label><?php echo app('translator')->get('Select Categorie'); ?></label>
                            <select class="form-control" name="categorie" onchange="getProductNameAdd()" required id="categorieAdd">
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categorie->id); ?>" data-categ="<?php echo e(__($categorie->name)); ?>" data-price="<?php echo e(__($categorie->price)); ?>" data-unit="<?php echo e(__($categorie->unite->name)); ?>"><?php echo e(__($categorie->name)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom'); ?></label>
                            <input type="text" class="form-control" name="name" required readonly>
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
                        <button type="submit" class="btn btn--primary w-100 h-45 "><?php echo app('translator')->get("Envoyer"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updateCategorieModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Update Livraison produit'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('admin.livraison.categorie.produit.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Select Categorie'); ?></label>
                            <select class="form-control" name="categorie" onchange="getProductName()" required id="categorieUpdate">
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categorie->id); ?>" data-categ="<?php echo e(__($categorie->name)); ?>" data-price="<?php echo e(__($categorie->price)); ?>" data-unit="<?php echo e(__($categorie->unite->name)); ?>" ><?php echo e(__($categorie->name)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom'); ?></label>
                            <input type="text" class="form-control" name="name"  placeholder="<?php echo app('translator')->get('Enter Name'); ?>"
                                required readonly>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Prix'); ?></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Enter Price'); ?>" name="price"
                                    required>
                                <span class="input-group-text"><?php echo e(__($general->cur_text)); ?></span>
                            </div>
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
    <button class="btn btn-sm btn-outline--primary addCategorie"><i class="las la-plus"></i><?php echo app('translator')->get("Créer un nouveau"); ?></button>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
      
        function getProductName() { 
    
        let categorie = $('#categorieUpdate').find(':selected').data('categ');
        let unite = $('#categorieUpdate').find(':selected').data('unit'); 
        let price = $('#categorieUpdate').find(':selected').data('price');
       
        product = unite+'-'+categorie;
        $('input[name=name]').val(product).attr("readonly",'readonly'); 
        $('input[name=price]').val(price).attr("readonly",'readonly'); 
        
    }
    function getProductNameAdd() { 
    
    let categorie = $('#categorieAdd').find(':selected').data('categ');
    let unite = $('#categorieAdd').find(':selected').data('unit'); 
    let price = $('#categorieUpdate').find(':selected').data('price');
     
    product = unite+'-'+categorie;
    $('input[name=name]').val(product).attr("readonly",'readonly'); 
    $('input[name=price]').val(price).attr("readonly",'readonly'); 
}
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
                modal.find('select[name=categorie]').val($(this).data('categorie'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/categorie/produit.blade.php ENDPATH**/ ?>