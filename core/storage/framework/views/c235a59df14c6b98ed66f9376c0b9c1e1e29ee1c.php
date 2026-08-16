<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
               <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Numéro de Commande'); ?></label>
                                <input type="text" name="search" value="<?php echo e(request()->search); ?>" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Staff'); ?></label>
                                <select name="staff" class="form-control">
                                    <option value=""><?php echo app('translator')->get("Tous"); ?></option>
                                    <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($staff->id); ?>" <?php echo e(request()->staff == $staff->id ? 'selected' : ''); ?> >
                                        <?php echo e($staff->lastname); ?> <?php echo e($staff->firstname); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Status de livraison'); ?></label>
                                <select name="status" class="form-control">
                                    <option value=""><?php echo app('translator')->get("Tous"); ?></option>
                                    <option value="2"><?php echo app('translator')->get('En attente'); ?></option>
                                    <option value="3"><?php echo app('translator')->get('Livré'); ?></option>
                                    <option value="1"><?php echo app('translator')->get('Annulé'); ?></option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Status de paiement'); ?></label>
                                <select name="payment_status" class="form-control">
                                    <option value="" selected><?php echo app('translator')->get("Tous"); ?></option>
                                    <option value="1"><?php echo app('translator')->get('Payé'); ?></option>
                                    <option value="0"><?php echo app('translator')->get('Impayé'); ?></option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label><?php echo app('translator')->get('Date'); ?></label>
                                <input name="date" type="text" class="date form-control" placeholder="<?php echo app('translator')->get('Date de début - Date de Fin'); ?>" autocomplete="off" value="<?php echo e(request()->date); ?>">
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

                                    <th><?php echo app('translator')->get('Magasin Expéditeur - Staff'); ?></th>
                                    <th><?php echo app('translator')->get('Destinataire - Client'); ?></th>
                                    <th><?php echo app('translator')->get('Montant - Numéro Commande'); ?></th>
                                    <th><?php echo app('translator')->get("Date estimée d'envoi"); ?></th>
                                    <th><?php echo app('translator')->get('Status de paiement'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php $__empty_1 = true; $__currentLoopData = $livraisonLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livraisonInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                    <tr>

                                        <td>
                                            <span><?php echo e(__($livraisonInfo->senderMagasin->name ?? null)); ?></span><br>
                                            <?php echo e(__($livraisonInfo->senderStaff->fullname ?? null)); ?>

                                        </td>

                                        <td>
                                        <span class="fw-bold">
                                            <?php echo e(__($livraisonInfo->receiverClient->address)); ?>

                                            </span>
                                            <br>
                                            <?php if($livraisonInfo->receiver_client_id): ?>
                                                <?php echo e(__($livraisonInfo->receiverClient->name)); ?>

                                                <?php echo e(__($livraisonInfo->receiverClient->phone)); ?>

                                            <?php else: ?>
                                                <span><?php echo app('translator')->get('N/A'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold d-block">
                                                <?php echo e(showAmount(@$livraisonInfo->paymentInfo->final_amount)); ?>

                                                <?php echo e(__($general->cur_text)); ?>

                                            </span>
                                            <span><?php echo e($livraisonInfo->code); ?></span>
                                        </td>
                                        <td>
                                            <?php echo e(showDateTime($livraisonInfo->estimate_date, 'd M Y')); ?>

                                        </td>
                                        <td>
                                            <?php if(@$livraisonInfo->paymentInfo->status == Status::PAID): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('Payé'); ?></span>
                                            <?php elseif(@$livraisonInfo->paymentInfo->status == Status::UNPAID): ?>
                                                <span class="badge badge--danger"><?php echo app('translator')->get('Impayé'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($livraisonInfo->status == Status::COURIER_QUEUE): ?>
                                                <span class="badge badge--warning"><?php echo app('translator')->get("En attente d'expédition"); ?></span>
                                            <?php elseif($livraisonInfo->status == Status::COURIER_CANCEL): ?>
                                            <span class="badge badge--warning"><?php echo app('translator')->get('Annulé'); ?></span>
                                            <?php elseif($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE): ?>
                                                <span class="badge badge--danger"><?php echo app('translator')->get('En attente de reception'); ?></span>
                                            <?php elseif($livraisonInfo->status == Status::COURIER_DELIVERED): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('Livré'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo e(route('manager.livraison.invoice', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> <?php echo app('translator')->get('Facture'); ?></a>
                                            <a href="<?php echo e(route('manager.livraison.details', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> <?php echo app('translator')->get('Details'); ?></a>
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

                <?php if($livraisonLists->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($livraisonLists)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb-plugins'); ?>
    <badge class="btn btn-danger"><?php echo e(showAmount(@$sommeTotal)); ?> FCFA</badge>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/viseradmin/css/vendor/datepicker.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.fr.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.en.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";

            $('.date').datepicker({
                // maxDate:new Date(),
                range:true,
                multipleDatesSeparator:"-",
                language:'fr'
            });

            let url=new URL(window.location).searchParams;
            if(url.get('status') != undefined && url.get('status') != ''){
                $('select[name=status]').find(`option[value=${url.get('status')}]`).attr('selected',true);
            }
            if(url.get('payment_status') != undefined && url.get('payment_status') != ''){
                $('select[name=payment_status]').find(`option[value=${url.get('payment_status')}]`).attr('selected',true);
            }

        })(jQuery)

        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/livraison/list.blade.php ENDPATH**/ ?>