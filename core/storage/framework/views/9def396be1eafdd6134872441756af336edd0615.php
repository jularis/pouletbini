<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Image'); ?></th>
                                    <th><?php echo app('translator')->get('Nom'); ?></th>
                                    <th><?php echo app('translator')->get('Categorie'); ?></th>
                                    <th><?php echo app('translator')->get('Prix'); ?></th>
                                    <th><?php echo app('translator')->get('Quantite'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Last Update'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <?php if($produit->image != null): ?>
                                                <img src="<?php echo e(asset('core/storage/app/'.$produit->image)); ?>" style = " width: 100px;"/>
                                            <?php else: ?>
                                                <img src="<?php echo e(asset('assets/images/default.png')); ?>"
                                                    alt="image" style = " width: 100px;">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e(__($produit->name)); ?></span>
                                        </td>

                                        <td>
                                            <span><?php echo e(__($produit->categorie->name)); ?></span>
                                        </td>

                                        <td>
                                            <span><?php echo e(showAmount($produit->price)); ?> <?php echo e(__($general->cur_text)); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e(__($produit->quantity)); ?></span>
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
                                                data-quantity="<?php echo e(getAmount($produit->quantity)); ?>"
                                                data-categorie="<?php echo e($produit->categorie_id); ?>"
                                                data-description="<?php echo e($produit->description); ?>"><i
                                                    class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?></button>

                                            <?php if($produit->status == Status::DISABLE): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="<?php echo e(route('manager.livraison.categorie.produit.status', $produit->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer ce produit?'); ?>">
                                                    <i class="la la-eye"></i> <?php echo app('translator')->get("Activer"); ?>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="<?php echo e(route('manager.livraison.categorie.produit.status', $produit->id)); ?>"
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
                <form action="<?php echo e(route('manager.livraison.categorie.produit.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Select Categorie'); ?></label>
                            <select class="form-control" name="categorie" required>
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categorie->id); ?>"><?php echo e(__($categorie->name)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom'); ?></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Prix'); ?></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="price" required>
                                <span class="input-group-text"><?php echo e(__($general->cur_text)); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Quantity'); ?></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="quantity" required></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Image'); ?></label>
                            <div class="input-group mb-3">
                                <input type="file" name="picture" accept="image/*" class="form-control "
                                placeholder="Choisir une image" id="picture">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Description'); ?></label>
                            <div class="input-group mb-3">
                                <textarea class="form-control duree_formation" rows="4" name="description" cols="50" id="description"></textarea>
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
                <form action="<?php echo e(route('manager.livraison.categorie.produit.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id">
                    <div class="modal-body">

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Select Categorie'); ?></label>
                            <select class="form-control" name="categorie" required>
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categorie->id); ?>"><?php echo e(__($categorie->name)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom'); ?></label>
                            <input type="text" class="form-control" name="name" placeholder="<?php echo app('translator')->get('Entrer le nom du produit'); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Prix'); ?></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Entrer le Prix'); ?>" name="price"
                                    required>
                                <span class="input-group-text"><?php echo e(__($general->cur_text)); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Quantite'); ?></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Entrer la Quantité'); ?>" name="quantity"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Image'); ?></label>
                            <div class="input-group mb-3">
                                <input type="file" name="picture" accept="image/*" class="form-control "
                                placeholder="Choisir une image" id="image2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Description'); ?></label>
                            <div class="input-group mb-3">
                                <textarea class="form-control duree_formation" rows="4" name="description" cols="50" id="description2"></textarea>
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
                modal.find('textarea[name=description]').val($(this).data('description'));

                modal.modal('show');
            });
        })(jQuery);
    </script>
    <script>
 // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/categorie/produit.blade.php ENDPATH**/ ?>