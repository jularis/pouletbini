<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Entrer un nom'); ?></label>
                                <input type="text" name="search" value="<?php echo e(request()->search); ?>" class="form-control">
                            </div>
                             
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> <?php echo app('translator')->get('Filtrer'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Nom'); ?></th>
                                    <th><?php echo app('translator')->get('Genre'); ?></th>
                                    <th><?php echo app('translator')->get('Téléphone'); ?></th>
                                    <th><?php echo app('translator')->get('Email'); ?></th>
                                    <th><?php echo app('translator')->get('Adresse'); ?></th>
                                    <th><?php echo app('translator')->get('Itinéraire'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Last Update'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?php echo e(__($client->name)); ?></span>
                                        </td>

                                        <td>
                                            <span><?php echo e(__($client->genre)); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e(__($client->phone)); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e(__($client->email)); ?></span>
                                        </td>

                                        <td>
                                            <span><?php echo e(__($client->address)); ?></span>
                                        </td>
<td>
                                            <?php if($client->longitude): ?>
                                            <span><a href="https://maps.google.com?daddr=<?php echo e(__($client->latitude)); ?>,<?php echo e(__($client->longitude)); ?>" target="_blank"><i class="fa fa-map-marker"></i> Voir l'itinéraire
    </a></span><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                echo $client->statusBadge;
                                            ?>
                                        </td>

                                        <td>
                                            <span class="d-block"><?php echo e(showDateTime($client->updated_at)); ?></span>
                                            <span><?php echo e(diffForHumans($client->updated_at)); ?></span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary  updateCategorie"
                                                data-id="<?php echo e($client->id); ?>" 
                                                data-name="<?php echo e($client->name); ?>"
                                                data-genre="<?php echo e($client->genre); ?>"
                                                data-phone="<?php echo e($client->phone); ?>"
                                                data-address="<?php echo e($client->address); ?>"
                                                data-email="<?php echo e($client->email); ?>"
                                                data-longitude="<?php echo e($client->longitude); ?>"
                                                data-latitude="<?php echo e($client->latitude); ?>"
                                                ><i
                                                    class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?></button>

                                            <?php if($client->status == Status::DISABLE): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="<?php echo e(route('manager.livraison.categorie.client.status', $client->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer ce client?'); ?>">
                                                    <i class="la la-eye"></i> <?php echo app('translator')->get("Activer"); ?>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="<?php echo e(route('manager.livraison.categorie.client.status', $client->id)); ?>"
                                                    data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir désactiver ce client?'); ?>">
                                                    <i class="la la-eye-slash"></i><?php echo app('translator')->get("Désactiver"); ?>
                                                </button>
                                            <?php endif; ?>
                                            <a href="javascript:void();"
                                                class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="<?php echo e(route('manager.livraison.categorie.client.delete', encrypt($client->id))); ?>"
                                                data-question="<?php echo app('translator')->get('Êtes-vous sûr de supprimer ce client?'); ?>"
                                                ><i
                                                    class="las la-trash"></i><?php echo app('translator')->get('Supprimer'); ?></a>
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
                <?php if($clients->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($clients)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="categorieModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Ajouter Livraison client'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="<?php echo e(route('manager.livraison.categorie.client.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom & Prénom(s)'); ?></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Select Genre'); ?></label>
                            <select class="form-control" name="genre" required>
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <option value="<?php echo e(__('Homme')); ?>"><?php echo e(__('Homme')); ?></option> 
                                <option value="<?php echo e(__('Femme')); ?>"><?php echo e(__('Femme')); ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Téléphone'); ?></label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Email'); ?></label>
                            <input type="text" class="form-control" name="email" >
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Adresse'); ?></label>
                            <input type="text" class="form-control" name="address" >
                        </div>
                        <div class="form-group">
            <?php echo e(Form::label(__('Longitude'), null, ['class' => 'control-label'])); ?> 
            <?php echo Form::text('longitude', null, array('placeholder' => __('Longitude'),'class' => 'form-control','id'=>'longitude')); ?> 
    </div>
    <div class="form-group">
            <?php echo e(Form::label(__('Latitude'), null, ['class' => 'control-label'])); ?> 
            <?php echo Form::text('latitude', null, array('placeholder' => __('Latitude'),'class' => 'form-control','id'=>'latitude')); ?> 
    </div>
    <div class="form-group">
            <?php echo e(Form::label(__(''), null, ['class' => 'control-label'])); ?> 
            <p id="status"></p>
            <a href="javascript:void(0)" id="find-me" class="btn btn--info">Obtenir les coordonnées GPS</a> 
    </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 "><?php echo app('translator')->get("Envoyer"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

 

    <div id="typeModel" class="modal fade" tabindex="-1" role="dialog" style="z-index: 2147483647 !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Importer des clients'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="<?php echo e(route('manager.livraison.categorie.client.uploadcontent')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">   
                        <p>Fichier d'exemple à utiliser :<a href="<?php echo e(asset('assets/clients-import-exemple.xlsx')); ?>" target="_blank"><?php echo app('translator')->get('clients-import-exemple.xlsx'); ?></a></p>
                  

        <div class="form-group row"> 
            <label class="control-label col-sm-4"><?php echo e(__('Fichier(.xls, .xlsx)')); ?></label>
            <div class="col-xs-12 col-sm-8 col-md-8">
            <input type="file" name="uploaded_file" accept=".xls, .xlsx" class="form-control dropify-fr" placeholder="Choisir une image" id="image" required> 
        </div>
    </div>
    
 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 "><?php echo app('translator')->get('Envoyer'); ?></button>
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
    <a class="btn btn-sm btn-outline--warning addType"><i class="las la-cloud-upload-alt"></i> Importer des clients</a>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.addType').on('click', function() {
                $('#typeModel').modal('show');
            });
            $('.addCategorie').on('click', function() {
                $('#categorieModel').modal('show');
            });

            $('.updateCategorie').on('click', function() {
                var modal = $('#categorieModel');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=phone]').val($(this).data('phone'));
                modal.find('input[name=email]').val($(this).data('email'));
                modal.find('input[name=address]').val($(this).data('address'));
                modal.find('select[name=genre]').val($(this).data('genre'));
                modal.find('input[name=latitude]').val($(this).data('latitude'));
                modal.find('input[name=longitude]').val($(this).data('longitude'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/categorie/client.blade.php ENDPATH**/ ?>