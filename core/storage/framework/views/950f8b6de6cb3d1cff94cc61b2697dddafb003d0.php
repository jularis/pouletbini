<?php $__env->startSection('panel'); ?>
    <div class="row gy-4">
         
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="<?php echo e(route('manager.livraison.delivery.queue')); ?>" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lab la-accessible-icon f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small"><?php echo app('translator')->get('En attente de reception'); ?></span>
                            <h2 class="text-white"><?php echo e($deliveryQueueCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
     

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--deep-purple has-link box--shadow2">
                <a href="<?php echo e(route('manager.livraison.manage.delivered')); ?>" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-list-alt f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small"><?php echo app('translator')->get('Total Livré'); ?></span>
                            <h2 class="text-white"><?php echo e($livraisonDelivered); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--teal has-link box--shadow2">
                <a href="<?php echo e(route('manager.livraison.manage.list')); ?>" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-shipping-fast f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small"><?php echo app('translator')->get('Toutes les Commandes'); ?></span>
                            <h2 class="text-white"><?php echo e($livraisonInfoCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--primary has-link overflow-hidden box--shadow2">
                <a href="<?php echo e(route('manager.staff.index')); ?>" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-user-friends f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small"><?php echo app('translator')->get('Total Staff'); ?></span>
                            <h2 class="text-white"><?php echo e($totalStaffCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--lime has-link box--shadow2">
                <a href="<?php echo e(route('manager.magasin.index')); ?>" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-university f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small"><?php echo app('translator')->get("Total Magasin"); ?></span>
                            <h2 class="text-white"><?php echo e($magasinCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="<?php echo e(route('manager.magasin.income')); ?>" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small"><?php echo app('translator')->get('Total Revenus'); ?></span>
                            <h2 class="text-white"><?php echo e(showAmount($magasinIncome)); ?> <?php echo e($general->cur_sym); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->





    </div><!-- row end-->

    <!-- <div class="row mt-50">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Magasin Expéditeur - Staff'); ?></th>
                                    <th><?php echo app('translator')->get('Magasin Destinataire - Client'); ?></th>
                                    <th><?php echo app('translator')->get('Montant - Numéro Commande'); ?></th>
                                    <th><?php echo app('translator')->get('Date de création'); ?></th>
                                    <th><?php echo app('translator')->get('Status de paiement'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $livraisonInfos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livraisonInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span><?php echo e(__($livraisonInfo->senderMagasin->name)); ?></span><br>
                                            <a href="<?php echo e(route('manager.staff.edit', encrypt($livraisonInfo->senderStaff->id))); ?>"><span>@</span><?php echo e(__($livraisonInfo->senderStaff->username)); ?></a>
                                        </td>

                                        <td>
                                            <span>
                                                <?php if($livraisonInfo->receiver_magasin_id): ?>
                                                    <?php echo e(__($livraisonInfo->receiverMagasin->name)); ?>

                                                <?php else: ?>
                                                    <?php echo app('translator')->get('N/A'); ?>
                                                <?php endif; ?>
                                            </span>
                                            <br>
                                            <?php if($livraisonInfo->receiver_staff_id): ?>
                                                <a href="<?php echo e(route('manager.staff.edit', encrypt($livraisonInfo->receiverStaff->id))); ?>"><span>@</span><?php echo e(__($livraisonInfo->receiverStaff->username)); ?></a>
                                            <?php else: ?>
                                                <span><?php echo app('translator')->get('N/A'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span class="fw-bold"><?php echo e(showAmount(@$livraisonInfo->paymentInfo->final_amount)); ?>

                                                <?php echo e(__($general->cur_text)); ?></span><br>
                                            <span><?php echo e($livraisonInfo->code); ?></span>
                                        </td>

                                        <td>
                                            <span><?php echo e(showDateTime($livraisonInfo->created_at, 'd M Y')); ?></span><br>
                                            <span><?php echo e(diffForHumans($livraisonInfo->created_at)); ?></span>
                                        </td>

                                        <td>
                                            <?php if($livraisonInfo->paymentInfo->status == Status::PAID): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('Payé'); ?></span>
                                            <?php elseif($livraisonInfo->paymentInfo->status == Status::UNPAID): ?>
                                                <span class="badge badge--danger"><?php echo app('translator')->get('Impayé'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($livraisonInfo->status == Status::COURIER_QUEUE): ?>
                                                <?php if(auth()->user()->magasin_id == $livraisonInfo->sender_magasin_id): ?>
                                                    <span class="badge badge--danger"><?php echo app('translator')->get("En attente d'envoi"); ?></span>
                                                <?php else: ?>
                                                    <span></span>
                                                <?php endif; ?>
                                            <?php elseif($livraisonInfo->status == Status::COURIER_DISPATCH): ?>
                                                <?php if(auth()->user()->magasin_id == $livraisonInfo->sender_magasin_id): ?>
                                                    <span class="badge badge--warning"><?php echo app('translator')->get('Expédiée'); ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge--warning"><?php echo app('translator')->get('Encours'); ?></span>
                                                <?php endif; ?>
                                            <?php elseif($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE): ?>
                                                <span class="badge badge--primary"><?php echo app('translator')->get("En attente de reception"); ?></span>
                                            <?php elseif($livraisonInfo->status == Status::COURIER_DELIVERED): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get("Livré"); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo e(route('manager.livraison.invoice', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> <?php echo app('translator')->get('Facture'); ?></a>
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
            </div>
        </div>
    </div> -->
<?php $__env->stopSection(); ?>


<?php $__env->startPush('breadcrumb-plugins'); ?>
    <div class="d-flex flex-wrap justify-content-end">
        <h3><?php echo e(__(auth()->user()->magasin->name)); ?></h3>
    </div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/dashboard.blade.php ENDPATH**/ ?>