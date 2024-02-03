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
                                    
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $livraisonLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livraisonInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?php echo e(__($livraisonInfo->senderMagasin->name)); ?></span><br>
                                            <?php echo e(__($livraisonInfo->senderStaff->fullname)); ?>

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
                                            <span class="fw-bold"><?php echo e(showAmount(@$livraisonInfo->paymentInfo->final_amount)); ?>

                                                <?php echo e(__($general->cur_text)); ?></span><br>
                                            <span><?php echo e($livraisonInfo->code); ?></span>
                                        </td>
 
                                        <td>
                                            <?php echo e(showDateTime($livraisonInfo->estimate_date, 'd M Y')); ?>

                                        </td>

                                        <td>
                                        <?php if($livraisonInfo->paymentInfo->status == Status::PAID): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('Payé'); ?></span>
                                            <?php elseif($livraisonInfo->paymentInfo->status == Status::PARTIAL): ?>
                                                <span class="badge badge--primary"><?php echo app('translator')->get('Partiel'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge--danger"><?php echo app('translator')->get('Impayé'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                        <a href="<?php echo e(route('manager.livraison.edit', encrypt($livraisonInfo->id))); ?>"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-pen"></i><?php echo app('translator')->get('Edit'); ?>
                                            </a>
                                            <?php if($livraisonInfo->paymentInfo->status == Status::UNPAID): ?>
                                            <?php echo Form::open(['method' => 'DELETE','route' => ['manager.livraison.delete', encrypt($livraisonInfo->id)],'style'=>'display:inline']); ?>

                                            <button class="btn btn-sm btn-outline--danger" type="submit"  onclick="return confirm('Etes vous sûr de vouloir supprimer cette commande?')"><i class="las la-trash"></i>Delete</button>
                    <?php echo Form::close(); ?>

                    <?php endif; ?>
                                            <a href="<?php echo e(route('manager.livraison.invoice', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> <?php echo app('translator')->get('Facture'); ?></a>
                                            <a href="<?php echo e(route('manager.livraison.details', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> <?php echo app('translator')->get('Details'); ?></a>
                                         <?php if($livraisonInfo->paymentInfo->status == 0 || $livraisonInfo->paymentInfo->status == 2): ?>
                                                <button class="btn btn-sm btn-outline--success  payment"
                                                    data-code="<?php echo e($livraisonInfo->code); ?>" data-finalamount="<?php echo e($livraisonInfo->paymentInfo->final_amount); ?>" data-partialamount="<?php echo e($livraisonInfo->paymentInfo->partial_amount); ?>"><i class="las la-credit-card"></i>
                                                    <?php echo app('translator')->get('Confirmer le Paiement'); ?></button>
                                            <?php endif; ?>
                                            <?php if($livraisonInfo->status == 2): ?>
                                                <button class="btn btn-sm btn-outline--secondary  delivery"
                                                    data-code="<?php echo e($livraisonInfo->code); ?>"><i class="las la-truck"></i>
                                                    <?php echo app('translator')->get('Terminer la livraison'); ?></button>
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

                <?php if($livraisonLists->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($livraisonLists)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

 
    <?php if(session('codePaie')): ?>
    <div class="modal fade" id="paymentByAuto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelAuto"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabelAuto"><?php echo app('translator')->get('Payment Confirmation'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('manager.livraison.payment')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <input type="hidden" name="code" value="<?php echo e(session('codePaie')); ?>">
                    <div class="modal-body">
                    <div class="swal2-header">
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant total:&nbsp;<span id="recu"></span>&nbsp;FCFA</h2>
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant restant:&nbsp;<span id="restant"></span>&nbsp;FCFA</h2>
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Entrer le montant reçu</h2>
                        </div> 
                       <div class="swal2-content">
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="1" max="" id="montant" required></p>
                       </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get("Non"); ?></button>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get("Oui"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function(){
        $("#paymentByAuto").modal('show');
    });
 
</script>
<?php $__env->stopPush(); ?>
    <?php else: ?>
    <div class="modal fade" id="paymentBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel"><?php echo app('translator')->get('Payment Confirmation'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('manager.livraison.payment')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <input type="hidden" name="code">
                    <div class="modal-body">
                    <div class="swal2-header">
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant total:&nbsp;<span id="recu"></span>&nbsp;FCFA</h2>
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant restant:&nbsp;<span id="restant"></span>&nbsp;FCFA</h2>
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Entrer le montant reçu</h2>
                        </div> 
                       <div class="swal2-content">
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="1" max="" id="montant" required></p>
                       </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get("Non"); ?></button>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get("Oui"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->startPush('script'); ?>
    <script>
        (function($) {
  
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                $('#recu').html($(this).data('finalamount')) 
                $('#restant').html($(this).data('finalamount')-$(this).data('partialamount'))
                modal.find('input[name=montant]').prop('max',$(this).data('finalamount')-$(this).data('partialamount'))
                modal.modal('show');
            }); 
        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>
    <?php endif; ?>

    <?php if(session('code')): ?>
     
    <div class="modal fade" id="deliveryByAuto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelAuto"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabelAuto"><?php echo app('translator')->get('Confirmation de reception'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <form action="<?php echo e(route('manager.livraison.delivery')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <input type="hidden" name="code" value="<?php echo e(session('code')); ?>">
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Etre-vous sûr de vouloir confirmer la reception de cette livraison?'); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get('Fermer'); ?></button>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Confirmer'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function(){
        $("#deliveryByAuto").modal('show');
    });
 
</script>
<?php $__env->stopPush(); ?> 
    <?php else: ?>
    <div class="modal fade" id="deliveryBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel"><?php echo app('translator')->get('Confirmation de reception'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <form action="<?php echo e(route('manager.livraison.delivery')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Etre-vous sûr de vouloir confirmer la reception de cette livraison?'); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get('Fermer'); ?></button>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Confirmer'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->startPush('script'); ?>
    <script>
        (function($) { 
            $('.delivery').on('click', function() {
                var modal = $('#deliveryBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>
    <?php endif; ?>
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
            

            $('.date').datepicker({
                // maxDate:new Date(),
                range:true,
                multipleDatesSeparator:"-",
                language:'fr'
            });

           
        })(jQuery)
        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
<style type="text/css">
.swal2-input[type=number] {
    min-width: 15em;
    margin: 0px auto;
}
.swal2-file, .swal2-input, .swal2-textarea {
    box-sizing: border-box; 
    transition: border-color .3s,box-shadow .3s;
    border: 1px solid #d9d9d9;
    border-radius: 0.1875em;
    background: inherit;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.06);
    color: inherit;
    font-size: 20px !important;
}
.swal2-input {
    height: 4.625em;
    padding: 0 0.75em;
}
.swal2-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 1.8em;
}
.swal2-title {
    position: relative;
    max-width: 100%;
    margin: 0 0 0.4em;
    padding: 0;
    color: #595959;
    font-size: 1.4em;
    font-weight: 600;
    text-align: center;
    text-transform: none;
    word-wrap: break-word;
}
.swal2-content {
    z-index: 1;
    justify-content: center;
    margin: 0;
    padding: 0 1.6em;
    color: #545454;
    font-size: 1.125em;
    font-weight: 400;
    line-height: normal;
    text-align: center;
    word-wrap: break-word;
}
</style>

<?php $__env->stopPush(); ?>
 
<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/livraison/deliveryQueue.blade.php ENDPATH**/ ?>