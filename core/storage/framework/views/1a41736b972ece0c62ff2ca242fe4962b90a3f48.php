<?php $__env->startSection('panel'); ?>
    <div class="card">
        <div class="card-body">
            <div id="printInvoice">
                <div class="content-header">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">
                            <?php echo app('translator')->get("Numéro Facture"); ?>:
                            <small>#<?php echo e($livraisonInfo->invoice_id); ?></small>
                            <br>
                            <?php echo app('translator')->get('Date'); ?>:
                            <?php echo e(showDateTime($livraisonInfo->created_at, 'd M Y')); ?>

                            <br>
                            <?php echo app('translator')->get("Date estimée d'envoi"); ?>:
                            <?php echo e(showDateTime($livraisonInfo->estimate_date, 'd M Y')); ?>

                        </div>
                        <div>
                        </div>
                    </div>
                </div>

                <div class="invoice">
                    <div class="d-flex justify-content-between mt-3">
                        <div class="text-center">
                        <?php echo QrCode::size(150)->generate($livraisonInfo->code); ?>
                        </div>
                        <div>
                            <b><?php echo app('translator')->get("N° Commande"); ?>:</b> <?php echo e($livraisonInfo->code); ?><br>
                            <b><?php echo app('translator')->get('Status de paiement'); ?>:</b>
                            <?php if($livraisonInfo->payment->status == Status::PAID): ?>
                                <span class="badge badge--success"><?php echo app('translator')->get('Payé'); ?></span>
                            <?php else: ?>
                                <span class="badge badge--danger"><?php echo app('translator')->get('Impayé'); ?></span>
                            <?php endif; ?>
                            <br>
                            <b><?php echo app('translator')->get('Sender Magasin'); ?>:</b> <?php echo e(__($livraisonInfo->senderMagasin->name)); ?><br>
                            <b><?php echo app('translator')->get('Receiver Magasin'); ?>:</b> <?php echo e(__($livraisonInfo->receiverMagasin->name)); ?>

                        </div>
                    </div>
                    <hr>
                    <div class="invoice-info d-flex justify-content-between">
                        <div>
                            <?php echo app('translator')->get("De"); ?>
                            <address>
                                <strong><?php echo e(__($livraisonInfo->sender_name)); ?></strong><br>
                                <?php echo e(__($livraisonInfo->sender_address)); ?><br>
                                <?php echo app('translator')->get('Téléphone'); ?>: <?php echo e($livraisonInfo->sender_phone); ?><br>
                                <?php echo app('translator')->get('Email'); ?>: <?php echo e($livraisonInfo->sender_email); ?>

                            </address>
                        </div>
                        <div>
                            <?php echo app('translator')->get("A"); ?>
                            <address>
                                <strong><?php echo e(__($livraisonInfo->receiver_name)); ?></strong><br>
                                <?php echo e(__($livraisonInfo->receiver_address)); ?><br>
                                <?php echo app('translator')->get('Téléphone'); ?>: <?php echo e($livraisonInfo->receiver_phone); ?><br>
                                <?php echo app('translator')->get('Email'); ?>: <?php echo e($livraisonInfo->receiver_email); ?>

                            </address>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo app('translator')->get('Produit'); ?></th>
                                        <th><?php echo app('translator')->get('Prix'); ?></th>
                                        <th><?php echo app('translator')->get('Qte'); ?></th>
                                        <th><?php echo app('translator')->get('Sous-total'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $livraisonInfo->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livraisonProductInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(__(@$livraisonProductInfo->produit->name)); ?></td>
                                            <td><?php echo e(showAmount($livraisonProductInfo->fee)); ?> <?php echo e($general->cur_sym); ?></td>
                                            <td><?php echo e($livraisonProductInfo->qty); ?> <?php echo e(__(@$livraisonProductInfo->produit->categorie->name)); ?></td>
                                            <td><?php echo e(showAmount($livraisonProductInfo->fee)); ?> <?php echo e($general->cur_sym); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-30 mb-none-30">
                        <div class="col-lg-12 mb-30">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th><?php echo app('translator')->get('Sous-total'); ?>:</th>
                                            <td><?php echo e(showAmount($livraisonInfo->payment->amount)); ?> <?php echo e($general->cur_sym); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo app('translator')->get('Reduction'); ?>:</th>
                                            <td><?php echo e(showAmount($livraisonInfo->payment->discount)); ?> <?php echo e($general->cur_sym); ?>

                                                <small class="text--danger">
                                                     
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo app('translator')->get('Frais de livraison'); ?>:</th>
                                            <td><?php echo e(showAmount($livraisonInfo->payment->frais_livraison)); ?> <?php echo e($general->cur_sym); ?> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo app('translator')->get('Total'); ?>:</th>
                                            <td><?php echo e(showAmount($livraisonInfo->payment->final_amount)); ?> <?php echo e($general->cur_sym); ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            <div class="row no-print">
                <div class="col-sm-12">
                    <div class="float-sm-end">
                        <?php if($livraisonInfo->paymentInfo->status == Status::UNPAID): ?>
                            <button type="button" class="btn btn-outline--success m-1 payment"
                                data-code="<?php echo e($livraisonInfo->code); ?>">
                                <i class="fa fa-credit-card"></i> <?php echo app('translator')->get("Effectuer le Paiement"); ?>
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-outline--primary m-1 printInvoice">
                            <i class="las la-download"></i><?php echo app('translator')->get("Imprimer"); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="paymentBy" tabindex="-1" role="dialog" a>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel"><?php echo app('translator')->get('Confirmation de Paiement'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i> </button>
                </div>

                <form action="<?php echo e(route('manager.livraison.payment')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Etes-vous sûr d\'avoir encassé ce montant?'); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get("Non"); ?></button>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get("Oui"); ?></button>
                    </div>
                </form>
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

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.printInvoice').click(function() {
                $("#printInvoice").printThis();
            });
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/livraison/invoice.blade.php ENDPATH**/ ?>