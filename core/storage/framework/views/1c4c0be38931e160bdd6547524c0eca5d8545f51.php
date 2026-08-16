<?php $__env->startSection('panel'); ?>
    <div class="row">
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
                                            <span class="fw-bold"><?php echo e(__($livraisonInfo->senderMagasin->name ?? null)); ?></span><br>
                                            <?php echo e(__($livraisonInfo->senderStaff->fullname ?? null)); ?>

                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                <?php if($livraisonInfo->receiver_magasin_id): ?>
                                                    <?php echo e(__($livraisonInfo->receiverMagasin->name)); ?>

                                                <?php else: ?>
                                                    <?php echo app('translator')->get('N/A'); ?>
                                                <?php endif; ?>
                                            </span>
                                            <br>
                                            <?php if($livraisonInfo->receiver_client_id): ?>
                                                <?php echo e(__($livraisonInfo->receiver_name)); ?><br>
                                                <a href="tel:<?php echo e($livraisonInfo->receiver_phone); ?>"><?php echo e($livraisonInfo->receiver_phone); ?></a>

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

                                            <a href="<?php echo e(route('staff.livraison.details', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> <?php echo app('translator')->get('Details'); ?></a>
                                        <?php if($livraisonInfo->estimate_date < gmdate('Y-m-d H:i:s')): ?>
                                             <a href="<?php echo e(route('staff.livraison.invoice', encrypt($livraisonInfo->id))); ?>"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> <?php echo app('translator')->get('Facture'); ?></a>
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
                                            <?php else: ?>
                                            <span class="badge badge--dark"><?php echo app('translator')->get('Prochaine livraison'); ?> le <?php echo e(showDateTime($livraisonInfo->estimate_date, 'd M Y')); ?></span>
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
                <form action="<?php echo e(route('staff.livraison.payment')); ?>" method="POST">
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
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="500" max="" id="montant" required></p>
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
                <form action="<?php echo e(route('staff.livraison.payment')); ?>" method="POST">
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
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="500" max="" id="montant" required></p>
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
                <form action="<?php echo e(route('staff.livraison.delivery')); ?>" method="POST">
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
                <form action="<?php echo e(route('staff.livraison.delivery')); ?>" method="POST">
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
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Livraison Code']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Livraison Code']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.date-filter','data' => ['placeholder' => 'Date de Début - Date de Fin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('date-filter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Date de Début - Date de Fin']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
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

<?php echo $__env->make('staff.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/poulnymi/new.pouletbini.com/core/resources/views/staff/livraison/deliveryQueue.blade.php ENDPATH**/ ?>