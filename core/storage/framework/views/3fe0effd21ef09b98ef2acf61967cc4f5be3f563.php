<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <h3><?php echo e($pageTitle); ?></h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Magasin Expéditeur</th>
                            <th>Staff Expéditeur</th>
                            <th>Destinataire</th>
                            <th>Client / Téléphone</th>
                            <th>Montant</th>
                            <th>Date estimée</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $livraisonLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($l->code); ?></td>
                                <td><?php echo e($l->senderMagasin->name ?? ''); ?></td>
                                <td><?php echo e($l->senderStaff->fullname ?? ''); ?></td>
                                <td><?php echo e($l->receiverMagasin->name ?? $l->receiver_name); ?></td>
                                <td><?php echo e($l->receiverClient->name ?? ''); ?> / <?php echo e($l->receiver_phone); ?></td>
                                <td><?php echo e(showAmount(@$l->paymentInfo->final_amount)); ?> <?php echo e(__($general->cur_text)); ?></td>
                                <td><?php echo e(showDateTime($l->estimate_date, 'd M Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    window.addEventListener('load', function(){
        window.print();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/livraison/deliveryQueuePdf.blade.php ENDPATH**/ ?>