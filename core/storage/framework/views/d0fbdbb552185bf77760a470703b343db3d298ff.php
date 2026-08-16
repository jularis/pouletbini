<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-10">
            <div class="row mb-30">
                <div class="col-lg-6 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark"><?php echo app('translator')->get('Informations Expéditeur'); ?></h5>
                        <div class="card-body">
                            <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Magasin'); ?>
                                    <span><?php echo e(__($livraisonInfo->receiverMagasin->name)); ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Nom'); ?>
                                    <span><?php echo e(__($livraisonInfo->sender_name)); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Email'); ?>
                                    <span><?php echo e(__($livraisonInfo->sender_email)); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Téléphone'); ?>
                                    <span><a href="tel:<?php echo e($livraisonInfo->sender_phone); ?>"><?php echo e($livraisonInfo->sender_phone); ?></a></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Adresse'); ?>
                                    <span><?php echo e(__($livraisonInfo->sender_address)); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Numéro de Commande'); ?>
                                    <span class="fw-bold"><?php echo e($livraisonInfo->code); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark"><?php echo app('translator')->get('Informations du Destinataire'); ?></h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Nom'); ?>
                                    <span><?php echo e(__($livraisonInfo->receiver_name)); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Email'); ?>
                                    <span><?php echo e($livraisonInfo->receiver_email); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Téléphone'); ?>
                                    <span><a href="tel:<?php echo e($livraisonInfo->receiver_phone); ?>"><?php echo e($livraisonInfo->receiver_phone); ?></a></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Adresse'); ?>
                                    <span><?php echo e(__($livraisonInfo->receiver_address)); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Status'); ?>
                                    <?php if($livraisonInfo->status != Status::COURIER_DELIVERED): ?>
                                        <span class="badge badge--primary fw-bold"><?php echo app('translator')->get('En attente'); ?></span>
                                    <?php elseif($livraisonInfo->status == Status::COURIER_DELIVERED): ?>
                                        <span class="badge badge--success"><?php echo app('translator')->get('Livré'); ?></span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark"><?php echo app('translator')->get('Details de la livraison'); ?></h5>
                        <div class="card-body">
                            <div class="table-responsive--md  table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                        <th><?php echo app('translator')->get('Catégorie'); ?></th>
                                            <th><?php echo app('translator')->get('Produit'); ?></th>
                                            <th><?php echo app('translator')->get('Prix'); ?></th>
                                            <th><?php echo app('translator')->get('Qte'); ?></th>
                                            <th><?php echo app('translator')->get('Sous-total'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $livraisonInfo->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livraisonProductInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(__(@$livraisonProductInfo->produit->categorie->name)); ?></td>
                                                <td><?php echo e(__($livraisonProductInfo->produit->name)); ?></td>
                                                <td><?php echo e(showAmount($livraisonProductInfo->type_price)); ?> <?php echo e($general->cur_sym); ?></td>
                                                <td><?php echo e($livraisonProductInfo->qty); ?></td>
                                                <td><?php echo e(showAmount($livraisonProductInfo->fee)); ?> <?php echo e($general->cur_sym); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-30">
                <div class="col-lg-12 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark"><?php echo app('translator')->get('Informations de Paiement'); ?></h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Paiement reçu par '); ?>
                                    <?php if(!empty($livraisonInfo->paymentInfo->magasin_id)): ?>
                                        <span><?php echo e(__(@$livraisonInfo->paymentInfo->magasin->name)); ?></span>
                                    <?php else: ?>
                                        <span><?php echo app('translator')->get('N/A'); ?></span>
                                    <?php endif; ?>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Date'); ?>
                                    <?php if(!empty($livraisonInfo->paymentInfo->date)): ?>
                                        <span><?php echo e(showDateTime($livraisonInfo->date, 'd M Y')); ?></span>
                                    <?php else: ?>
                                        <span><?php echo app('translator')->get('N/A'); ?></span>
                                    <?php endif; ?>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Sous-total'); ?>
                                    <span><?php echo e(showAmount($livraisonInfo->paymentInfo->amount)); ?>

                                        <?php echo e(__($general->cur_text)); ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Reduction'); ?>
                                    <span>
                                        <?php echo e(showAmount($livraisonInfo->paymentInfo->discount)); ?>

                                        <?php echo e(__($general->cur_text)); ?>

                                        <small class="text--danger">(<?php echo e(getAmount($livraisonInfo->payment->percentage)); ?>%)</small>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Frais de livraison'); ?>
                                    <span>
                                        <?php echo e(showAmount($livraisonInfo->paymentInfo->frais_livraison)); ?>

                                        <?php echo e(__($general->cur_text)); ?> 
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Total'); ?>
                                    <span><?php echo e(showAmount($livraisonInfo->paymentInfo->final_amount)); ?>

                                        <?php echo e(__($general->cur_text)); ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo app('translator')->get('Status'); ?>
                                    <?php if($livraisonInfo->paymentInfo->status == Status::PAID): ?>
                                        <span class="badge badge--success"><?php echo app('translator')->get('Payé'); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge--danger"><?php echo app('translator')->get('Impayé'); ?></span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(url()->previous()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(url()->previous()).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/livraison/details.blade.php ENDPATH**/ ?>